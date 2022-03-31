<?php

namespace App\Http\Controllers\MazuProcess;

use Throwable;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Models\MazuMaster\Product;
use Illuminate\Support\Facades\DB;
use App\Models\MazuMaster\Customer;
use App\Models\MazuMaster\PaidType;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\MazuProcess\PurchaseOrderCustomer;
use App\Models\MazuProcess\PurchaseOrderCustomerItem;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Setting\NumberingFormController;
use App\Http\Controllers\MazuProcess\GeneralLedgerController;

class PurchaseOrderCustomerController extends Controller
{
    public $MenuID = '031';
    public $objNumberingForm;
    public $generateType = 'F_PURCHASE_ORDER_CUSTOMER';
    public $objGl;

    public function __construct()
    {
        $this->objGl = new GeneralLedgerController();
        $this->objNumberingForm = new NumberingFormController();
    }

    public function listPOCustomer(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        $store_id = getStoreId();
        $customerList = Customer::where('store_id', $store_id)->where('is_active', 1)->get();
        $productList = Product::where('store_id', $store_id)->where('is_active', 1)->with('unit', 'stockWarehouse', 'stockWarehouse.warehouse')->get();
        $paidTypeList = PaidType::where('store_id', $store_id)->where('is_active', 1)->get();

        return view('mazuprocess.poCustomerTable', [
            'MenuID'            => $this->MenuID,
            'customerList'      => $customerList,
            'productList'       => $productList,
            'paidTypeList'       => $paidTypeList,
        ]);
    }

    public function loadProduct(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $productList = Product::where('store_id', getStoreId())->where('is_active', 1)
                    ->with('category', 'unit', 'stockWarehouse', 'stockWarehouse.warehouse')
                    ->orderBy('created_at', 'DESC')->get();

        return['data'=> $productList];
    }

    public function loadPOCustomer($start_date, $end_date){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $store_id = getStoreId();
        $poCustomerList = PurchaseOrderCustomer::where('is_active', 1)
                                        ->whereBetween('po_date', [$start_date, $end_date])
                                        ->where('store_id', $store_id)
                                        ->with('customer', 'items', 'items.product', 'items.product.unit')
                                        ->orderBy('po_date', 'desc')
                                        ->get();

        return['data'=> $poCustomerList];
    }

    public function addPOCustomer(Request $request)
    {
        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        DB::beginTransaction();
        try {
            $arrsuccess = 0;
            $po_customer_id = Uuid::uuid4()->toString();
            $po_number = "";
            $decPaid = floatval(0);
            $decRemain = floatval(0);
            $paidTypeId = null;
            if($request->is_process){
                $po_number = $this->objNumberingForm->GENERATE_FORM_NUMBER($this->generateType);
                $decPaid = $request->dec_paid_fin;
                $decRemain = $request->dec_remain;
                $paidTypeId = $request->paid_type_id;
            }


            $poc = PurchaseOrderCustomer::create([
                'po_customer_id'                => $po_customer_id,
                'po_number'                     => $po_number,
                'po_date'                       => $request->po_date,
                'due_date'                      => $request->due_date,
                'customer_id'                   => $request->customer_id,
                'description'                   => $request->description,
                'total_price'                   => $request->total_price,
                'percent_discount'              => $request->percent_discount,
                'total_price_after_discount'    => $request->total_price_after_discount,
                'ppn'                           => $request->ppn,
                'shipping_cost'                 => $request->shipping_cost,
                'grand_total'                   => (floatval($request->grand_total) - floatval($request->shipping_cost)),
                'grand_total_wshipping'         => $request->grand_total,
                'dec_paid'                      => $decPaid,
                'dec_remain'                    => $decRemain,
                'paid_type_id'                  => $paidTypeId,
                'is_so_created'                 => 0,
                'is_process'                    => $request->is_process,
                'is_draft'                      => $request->is_draft,
                'is_open'                       => 1,
                'is_void'                       => 0,
                'is_active'                     => 1,
                'store_id'                      => getStoreId(),
                'created_user'                  => Auth::User()->employee->employee_name,
            ]);

            if($poc){
                for ($i=0; $i<count($request->product_id); $i++ ){
                    $item = PurchaseOrderCustomerItem::create([
                        'po_customer_id'                => $po_customer_id,
                        'product_id'                    => $request->product_id[$i],
                        'qty_order'                     => $request->qty_order_item[$i],
                        'price'                         => $request->price_item[$i],
                        'percent_discount'              => $request->percent_discount_item[$i],
                        'total_price'                   => $request->total_price_item[$i],
                        'total_price_after_discount'    => $request->total_price_after_discount_item[$i],
                        'description'                   => $request->description_item[$i],
                        'order_item'                    => $arrsuccess,
                    ]);

                    if($item){
                        $arrsuccess++;
                    }
                }

                if($poc->is_process){
                    $this->objGl->creditPoCustomer($poc);
                }
            }

            if ($poc && $arrsuccess == count($request->product_id)){
                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'Add purchase order customer success.'], 200);
            } else {
                DB::rollback();
                return response()->json(['status' => 'Error', 'message' => 'Add purchase order customer failled, with a part error.' ], 202);
            }
        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }
    public function updatePOCustomer(Request $request)
    {
        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        DB::beginTransaction();
        try {
            $arrsuccess = 0;
            $po_number = "";
            $decPaid = floatval(0);
            $decRemain = floatval(0);
            $paidTypeId = null;
            if($request->is_process){
                $po_number = $this->objNumberingForm->GENERATE_FORM_NUMBER($this->generateType);
                $decPaid = $request->dec_paid_fin;
                $decRemain = $request->dec_remain;
                $paidTypeId = $request->paid_type_id;
            }

            $poc = PurchaseOrderCustomer::find($request->po_customer_id);
            $poc->update([
                'po_number'                     => $po_number,
                'po_date'                       => $request->po_date,
                'due_date'                      => $request->due_date,
                'customer_id'                   => $request->customer_id,
                'description'                   => $request->description,
                'total_price'                   => $request->total_price,
                'percent_discount'              => $request->percent_discount,
                'total_price_after_discount'    => $request->total_price_after_discount,
                'ppn'                           => $request->ppn,
                'shipping_cost'                 => $request->shipping_cost,
                'grand_total'                   => (floatval($request->grand_total) - floatval($request->shipping_cost)),
                'grand_total_wshipping'         => $request->grand_total,
                'dec_paid'                      => $decPaid,
                'dec_remain'                    => $decRemain,
                'paid_type_id'                  => $paidTypeId,
                'is_process'                    => $request->is_process,
                'is_draft'                      => $request->is_draft,
                'is_void'                       => 0,
                'is_open'                       => 1,
                'updated_user'                  => Auth::User()->employee->employee_name,
            ]);

            if($poc){
                $deletedRows = PurchaseOrderCustomerItem::where('po_customer_id', $poc->po_customer_id)->get();
                foreach ($deletedRows as $ls) {
                    $ls->delete();
                }

                for ($i=0; $i<count($request->product_id); $i++ ){
                    $item = PurchaseOrderCustomerItem::create([
                        'po_customer_id'                => $poc->po_customer_id,
                        'product_id'                    => $request->product_id[$i],
                        'qty_order'                     => $request->qty_order_item[$i],
                        'price'                         => $request->price_item[$i],
                        'percent_discount'              => $request->percent_discount_item[$i],
                        'total_price'                   => $request->total_price_item[$i],
                        'total_price_after_discount'    => $request->total_price_after_discount_item[$i],
                        'description'                   => $request->description_item[$i],
                        'order_item'                    => $arrsuccess,
                    ]);

                    if($item){
                        $arrsuccess++;
                    }
                }

                if($poc->is_process){
                    $this->objGl->creditPoCustomer($poc);
                }
            }

            if ($poc && $arrsuccess == count($request->product_id)){
                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'Add purchase order customer success.'], 200);
            } else {
                DB::rollback();
                return response()->json(['status' => 'Error', 'message' => 'Add purchase order customer failled, with a part error.' ], 202);
            }
        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }
    function deletePOCustomer($po_customer_id){
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            $poc = PurchaseOrderCustomer::find($po_customer_id);
            if ($poc){
                $poc->is_active = 0;
                $poc->is_process = 0;
                $poc->is_draft = 0;
                $poc->is_void = 0;
                $poc->is_open = 0;
                $poc->update();

                $this->objGl->creditPoCustomerDelete($poc);
                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'Delete purchase order customer success.'], 200);
            } else {
                DB::rollBack();
                return response()->json(['status' => 'Info', 'message' => 'delete purchase order customer failed.'], 200);
            }

        } catch (Throwable $e){
            DB::rollBack();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }
}
