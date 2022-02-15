<?php

namespace App\Http\Controllers\MazuProcess;

use Throwable;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Models\MazuMaster\Product;
use Illuminate\Support\Facades\DB;
use App\Models\MazuMaster\Supplier;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\MazuProcess\PurchaseOrderSupplier;
use App\Models\MazuProcess\PurchaseOrderSupplierItem;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Setting\NumberingFormController;
use App\Models\MazuProcess\PurchaseOrderCustomer;

class PurchaseOrderSupplierController extends Controller
{
    public $MenuID = '021';
    public $objNumberingForm;
    public $generateType = 'F_PURCHASE_ORDER_SUPPLIER';

    public function __construct()
    {
        $this->objNumberingForm = new NumberingFormController();
    }
    public function listPOSupplier(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        $store_id = getStoreId();
        $poCustomerList = PurchaseOrderCustomer::where('store_id', $store_id)->where('is_active', 1)->where('is_process', 1)->where('is_open', 1)->with('customer', 'items', 'items.product', 'items.product.unit')->get();
        $supplierList = Supplier::where('store_id', $store_id)->where('is_active', 1)->get();
        $productList = Product::where('store_id', $store_id)->where('is_active', 1)->with('unit', 'stockWarehouse', 'stockWarehouse.warehouse')->get();

        return view('mazuprocess.poSupplierTable', [
            'MenuID'            => $this->MenuID,
            'poCustomerList'      => $poCustomerList,
            'supplierList'      => $supplierList,
            'productList'       => $productList,
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

    public function loadPOSupplier($start_date, $end_date){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $store_id = getStoreId();
        $poSupplierList = PurchaseOrderSupplier::where('is_active', 1)
                                        ->whereBetween('po_date', [$start_date, $end_date])
                                        ->where('store_id', $store_id)
                                        ->with('supplier', 'items', 'items.product', 'items.product.unit', 'poCustomer.customer')
                                        ->orderBy('po_date', 'desc')
                                        ->get();

        return['data'=> $poSupplierList];
    }

    public function addPOSupplier(Request $request)
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
            $po_supplier_id = Uuid::uuid4()->toString();
            $po_number = "";
            if($request->is_process){
                $po_number = $this->objNumberingForm->GENERATE_FORM_NUMBER($this->generateType);
            }


            $pos = PurchaseOrderSupplier::create([
                'po_supplier_id'                => $po_supplier_id,
                'po_number'                     => $po_number,
                'po_customer_id'                => $request->po_customer_id,
                'po_date'                       => $request->po_date,
                'due_date'                      => $request->due_date,
                'supplier_id'                   => $request->supplier_id,
                'description'                   => $request->description,
                'total_price'                   => $request->total_price,
                'percent_discount'              => $request->percent_discount,
                'total_price_after_discount'    => $request->total_price_after_discount,
                'ppn'                           => $request->ppn,
                'grand_total'                   => $request->grand_total,
                'is_process'                    => $request->is_process,
                'is_draft'                      => $request->is_draft,
                'is_open'                       => 1,
                'is_void'                       => 0,
                'is_active'                     => 1,
                'store_id'                      => getStoreId(),
                'created_user'                  => Auth::User()->employee->employee_name,
            ]);

            if($pos){
                for ($i=0; $i<count($request->product_id); $i++ ){
                    $item = PurchaseOrderSupplierItem::create([
                        'po_supplier_id'                => $po_supplier_id,
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
            }

            if ($pos && $arrsuccess == count($request->product_id)){
                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'Add purchase order supplier success.'], 200);
            } else {
                DB::rollback();
                return response()->json(['status' => 'Error', 'message' => 'Add purchase order supplier failled, with a part error.' ], 202);
            }
        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }
    public function updatePOSupplier(Request $request)
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
            if($request->is_process){
                $po_number = $this->objNumberingForm->GENERATE_FORM_NUMBER($this->generateType);
            }

            $pos = PurchaseOrderSupplier::find($request->po_supplier_id);
            $pos->update([
                'po_number'                     => $po_number,
                'po_date'                       => $request->po_date,
                'po_customer_id'                => $request->po_customer_id,
                'due_date'                      => $request->due_date,
                'supplier_id'                   => $request->supplier_id,
                'description'                   => $request->description,
                'total_price'                   => $request->total_price,
                'percent_discount'              => $request->percent_discount,
                'total_price_after_discount'    => $request->total_price_after_discount,
                'ppn'                           => $request->ppn,
                'grand_total'                   => $request->grand_total,
                'is_process'                    => $request->is_process,
                'is_draft'                      => $request->is_draft,
                'is_void'                       => 0,
                'is_open'                       => 1,
                'updated_user'                  => Auth::User()->employee->employee_name,
            ]);

            if($pos){
                $deletedRows = PurchaseOrderSupplierItem::where('po_supplier_id', $pos->po_supplier_id)->get();
                foreach ($deletedRows as $ls) {
                    $ls->delete();
                }

                for ($i=0; $i<count($request->product_id); $i++ ){
                    $item = PurchaseOrderSupplierItem::create([
                        'po_supplier_id'                => $pos->po_supplier_id,
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
            }

            if ($pos && $arrsuccess == count($request->product_id)){
                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'Add purchase order supplier success.'], 200);
            } else {
                DB::rollback();
                return response()->json(['status' => 'Error', 'message' => 'Add purchase order supplier failled, with a part error.' ], 202);
            }
        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }
    function deletePOSupplier($po_supplier_id){
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            $pos = PurchaseOrderSupplier::find($po_supplier_id);
            if ($pos){
                $pos->is_active = 0;
                $pos->is_process = 0;
                $pos->is_draft = 0;
                $pos->is_void = 0;
                $pos->is_open = 0;
                $pos->update();
                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'Delete purchase order supplier success.'], 200);
            } else {
                DB::rollBack();
                return response()->json(['status' => 'Info', 'message' => 'delete purchase order supplier failed.'], 200);
            }

        } catch (Throwable $e){
            DB::rollBack();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }
}
