<?php

namespace App\Http\Controllers\MazuProcess;

use Throwable;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MazuMaster\Supplier;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\MazuMaster\ProductSupplier;
use App\Models\MazuProcess\PurchaseOrderMaterial;
use App\Models\MazuProcess\PurchaseOrderMaterialItem;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Setting\NumberingFormController;
use App\Http\Controllers\MazuProcess\GeneralLedgerController;

class PurchaseOrderMaterialController extends Controller
{
    public $MenuID = '022';
    public $objNumberingForm;
    public $generateType = 'F_PURCHASE_ORDER_MATERIAL_BAHAN';
    public $objGl;

    public function __construct()
    {
        $this->objGl = new GeneralLedgerController();
        $this->objNumberingForm = new NumberingFormController();
    }
    public function listPOMaterial(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        $store_id = getStoreId();
        $poMaterialList = PurchaseOrderMaterial::where('store_id', $store_id)->where('is_active', 1)->where('is_process', 1)->where('is_open', 1)->with('supplier', 'items', 'items.productSupplier', 'items.productSupplier.unit')->get();
        $supplierList = Supplier::where('store_id', $store_id)->where('is_active', 1)->get();

        return view('mazuprocess.poMaterialTable', [
            'MenuID'            => $this->MenuID,
            'poMaterialList'      => $poMaterialList,
            'supplierList'      => $supplierList,
        ]);
    }

    public function loadProductSupplier(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $producSuppliertList = ProductSupplier::where('store_id', getStoreId())
                    ->where('is_active', 1)
                    ->where('is_service', 0)
                    ->with('category', 'unit', 'stockWarehouse', 'stockWarehouse.warehouse')
                    ->orderBy('created_at', 'DESC')->get();

        return['data'=> $producSuppliertList];
    }

    public function loadPOMaterial($start_date, $end_date){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $store_id = getStoreId();
        $poMaterialList = PurchaseOrderMaterial::where('is_active', 1)
                                        ->whereBetween('po_date', [$start_date, $end_date])
                                        ->where('store_id', $store_id)
                                        ->with('supplier', 'items', 'items.productSupplier', 'items.productSupplier.unit')
                                        ->orderBy('po_date', 'desc')
                                        ->get();

        return['data'=> $poMaterialList];
    }

    public function addPOMaterial(Request $request)
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
            $po_material_id = Uuid::uuid4()->toString();
            $po_number = "";
            if($request->is_process){
                $po_number = $this->objNumberingForm->GENERATE_FORM_NUMBER($this->generateType);
            }


            $po = PurchaseOrderMaterial::create([
                'po_material_id'                => $po_material_id,
                'po_number'                     => $po_number,
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

            if($po){
                for ($i=0; $i<count($request->product_supplier_id); $i++ ){
                    $item = PurchaseOrderMaterialItem::create([
                        'po_material_id'                => $po_material_id,
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

                if($po->is_process){
                    $this->objGl->debitPoMaterial($po);
                }
            }

            if ($po && $arrsuccess == count($request->product_supplier_id)){
                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'Add purchase order material & bahan success.'], 200);
            } else {
                DB::rollback();
                return response()->json(['status' => 'Error', 'message' => 'Add purchase order material & bahan failled, with a part error.' ], 202);
            }
        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }
    public function updatePOMaterial(Request $request)
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

            $po = PurchaseOrderMaterial::find($request->po_material_id);
            $po->update([
                'po_number'                     => $po_number,
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
                'is_void'                       => 0,
                'is_open'                       => 1,
                'updated_user'                  => Auth::User()->employee->employee_name,
            ]);

            if($po){
                $deletedRows = PurchaseOrderMaterialItem::where('po_material_id', $po->po_material_id)->get();
                foreach ($deletedRows as $ls) {
                    $ls->delete();
                }

                for ($i=0; $i<count($request->product_supplier_id); $i++ ){
                    $item = PurchaseOrderMaterialItem::create([
                        'po_material_id'                => $po->po_material_id,
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
                if($po->is_process){
                    $this->objGl->debitPoMaterial($po);
                }
            }

            if ($po && $arrsuccess == count($request->product_supplier_id)){
                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'Add purchase order material & bahan success.'], 200);
            } else {
                DB::rollback();
                return response()->json(['status' => 'Error', 'message' => 'Add purchase order material & bahan failled, with a part error.' ], 202);
            }
        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }
    function deletePOMaterial($po_material_id){
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $po = PurchaseOrderMaterial::find($po_material_id);
            if ($po){
                $po->is_active = 0;
                $po->is_process = 0;
                $po->is_draft = 0;
                $po->is_void = 0;
                $po->is_open = 0;
                $po->update();
                $this->objGl->debitPoMaterialDelete($po);
                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'Delete purchase order material & bahan success.'], 200);
            } else {
                DB::rollBack();
                return response()->json(['status' => 'Info', 'message' => 'delete purchase order material & bahan failed.'], 200);
            }

        } catch (Throwable $e){
            DB::rollBack();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }
}
