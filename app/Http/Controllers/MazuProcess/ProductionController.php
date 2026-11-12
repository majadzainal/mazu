<?php

namespace App\Http\Controllers\MazuProcess;

use Throwable;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MazuMaster\Supplier;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\MazuProcess\Production;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\MazuMaster\ProductSupplier;
use App\Models\MazuProcess\ProductionItem;
use App\Models\MazuProcess\PurchaseOrderCustomer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Setting\NumberingFormController;
use App\Http\Controllers\MazuProcess\GeneralLedgerController;

class ProductionController extends Controller
{
    public $MenuID = '041';
    public $objNumberingForm;
    public $generateType = 'F_PRODUCTION';
    public $objGl;

    public function __construct()
    {
        $this->objGl = new GeneralLedgerController();
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
        // $poCustomerList = PurchaseOrderCustomer::where('store_id', $store_id)->where('is_active', 1)->where('is_process', 1)->where('is_open', 1)->with('customer', 'items', 'items.product', 'items.product.unit')->get();
        $supplierList = Supplier::where('store_id', $store_id)->where('is_active', 1)->get();
        $productSupplierList = ProductSupplier::where('store_id', $store_id)->where('is_active', 1)->with('unit', 'stockWarehouse', 'stockWarehouse.warehouse')->get();

        return view('mazuprocess.productionTable', [
            'MenuID'            => $this->MenuID,
            // 'poCustomerList'      => $poCustomerList,
            'supplierList'      => $supplierList,
            'productSupplierList'       => $productSupplierList,
        ]);
    }

    public function loadProductSupplier(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $productSupplierList = ProductSupplier::where('store_id', getStoreId())->where('is_active', 1)
                    ->with('category', 'unit', 'stockWarehouse', 'stockWarehouse.warehouse')
                    ->orderBy('created_at', 'DESC')->get();

        return['data'=> $productSupplierList];
    }

    public function loadProduction($start_date, $end_date){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $store_id = getStoreId();
        $productionList = Production::where('is_active', 1)
                                        ->whereBetween('po_date', [$start_date, $end_date])
                                        ->where('store_id', $store_id)
                                        ->with('supplier', 'items', 'items.product', 'items.product.unit', 'poCustomer.customer')
                                        ->orderBy('po_date', 'desc')
                                        ->get();

        return['data'=> $productionList];
    }

    public function addProduction(Request $request)
    {
        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        // dd($request);

        DB::beginTransaction();
        try {
            $arrsuccess = 0;
            $production_id = Uuid::uuid4()->toString();
            $po_number = "";
            if($request->is_process){
                $po_number = $this->objNumberingForm->GENERATE_FORM_NUMBER($this->generateType);
            }


            $pos = Production::create([
                'production_id'                 => $production_id,
                'po_number'                     => $po_number,
                // 'po_customer_id'                => $request->po_customer_id,
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
                for ($i=0; $i<count($request->product_supplier_id); $i++ ){
                    $item = ProductionItem::create([
                        'production_id'                 => $production_id,
                        'product_supplier_id'           => $request->product_supplier_id[$i],
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

                if($pos->is_process){
                    $this->objGl->debitProduction($pos);
                }
            }

            if ($pos && $arrsuccess == count($request->product_supplier_id)){
                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'Add production success.'], 200);
            } else {
                DB::rollback();
                return response()->json(['status' => 'Error', 'message' => 'Add production failled, with a part error.' ], 202);
            }
        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }
    public function updateProduction(Request $request)
    {
        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        DB::beginTransaction();
        try {
            $arrsuccess = 0;
            $po_number = "";
            if($request->is_process){
                $po_number = $this->objNumberingForm->GENERATE_FORM_NUMBER($this->generateType);
            }

            $prod = Production::find($request->production_id);
            $prod->update([
                'po_number'                     => $po_number,
                'po_date'                       => $request->po_date,
                // 'po_customer_id'                => $request->po_customer_id,
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

            if($prod){
                $deletedRows = ProductionItem::where('production_id', $prod->production_id)->get();
                foreach ($deletedRows as $ls) {
                    $ls->delete();
                }

                for ($i=0; $i<count($request->product_supplier_id); $i++ ){
                    $item = ProductionItem::create([
                        'production_id'                 => $prod->production_id,
                        'product_supplier_id'           => $request->product_supplier_id[$i],
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

                if($prod->is_process){
                    $this->objGl->debitProduction($prod);
                }
            }

            if ($prod && $arrsuccess == count($request->product_supplier_id)){
                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'Update production success.'], 200);
            } else {
                DB::rollback();
                return response()->json(['status' => 'Error', 'message' => 'Update production failled, with a part error.' ], 202);
            }
        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }
    function deleteProduction($production_id){
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        // dd($production_id);
        try {

            $prod = Production::find($production_id);
            if ($prod){
                $prod->is_active = 0;
                $prod->is_process = 0;
                $prod->is_draft = 0;
                $prod->is_void = 0;
                $prod->is_open = 0;
                $prod->update();
                $this->objGl->debitProductionDelete($prod);
                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'Delete production success.'], 200);
            } else {
                DB::rollBack();
                return response()->json(['status' => 'Info', 'message' => 'delete production failed.'], 200);
            }

        } catch (Throwable $e){
            DB::rollBack();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }
}
