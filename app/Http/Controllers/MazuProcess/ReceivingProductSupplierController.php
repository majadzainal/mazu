<?php

namespace App\Http\Controllers\MazuProcess;

use Throwable;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\MazuMaster\Warehouse;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\MazuProcess\PurchaseOrderMaterial;
use App\Http\Controllers\Log\LogPartStockController;
use App\Http\Controllers\MazuMaster\StockController;
use App\Models\MazuProcess\ReceivingProductSupplier;
use App\Models\MazuProcess\ReceivingProductSupplierItem;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Setting\NumberingFormController;

class ReceivingProductSupplierController extends Controller
{
    public $MenuID = '081';
    public $objStock;
    public $objNumberingForm;
    public $generateType = 'F_RECEIVING_PRODUCT_SUPPLIER';

    public function __construct()
    {
        $this->objStock = new StockController();
        $this->objLogPartStock = new LogPartStockController();
        $this->objNumberingForm = new NumberingFormController();
    }
    public function listRecProduct(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }
        $store_id = getStoreId();
        $poList = PurchaseOrderMaterial::where('is_open', 1)
                    ->where('store_id', $store_id)
                    ->where('is_active', 1)
                    ->with('items', 'supplier', 'items.productSupplier', 'items.productSupplier.unit', 'items.productSupplier.stockWarehouse', 'items.productSupplier.stockWarehouse.warehouse')
                    ->get();

        // dd($poList);
        $warehouseList = Warehouse::where('is_active', 1)->where('store_id', $store_id)->get();
        return view('mazuprocess.receivingProductSupplierTable', [
            'MenuID' => $this->MenuID,
            'poList' => $poList,
            'warehouseList' => $warehouseList,
        ]);

    }

    public function loadRecProduct($start_date, $end_date){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $recProductList = ReceivingProductSupplier::where('is_active', 1)
                                        ->with(["poMaterial" => function($q, $start_date, $end_date){
                                            $q->whereBetween('po_date', [$start_date, $end_date]);
                                        }])
                                        ->with('poMaterial', 'poMaterial.supplier')
                                        ->get();

        return['data'=> $recProductList];
    }

    public function addRecProduct(Request $request){
        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }
        // dd($request);
        DB::beginTransaction();
        try {

            $rec_prod_supplier_id = Uuid::uuid4()->toString();
            $rec_number = $this->objNumberingForm->GENERATE_FORM_NUMBER($this->generateType);
            $recProduct = ReceivingProductSupplier::create([
                'rec_prod_supplier_id'       => $rec_prod_supplier_id,
                'rec_number'                => $rec_number,
                'po_material_id'            =>  $request->po_material_id,
                'do_number_supplier'        => $request->do_number_supplier,
                'delivered_by'              => $request->delivered_by,
                'received_by'               => $request->received_by,
                'date_receive'              => $request->date_receive,
                'created_user'              => Auth::User()->employee->employee_name,
                'is_active'                 => 1,
            ]);

            $isClosePo = 0;

            if($recProduct){
                for ($i=0; $i<count($request->po_material_item_id); $i++ ){
                    $qtyRemainCount = $request->qty[$i] - ($request->qty_in[$i] + $request->qty_received[$i]);
                    $qtyOverCount = ($request->qty_in[$i] + $request->qty_received[$i]) - $request->qty[$i];
                    $qtyRemain = $qtyRemainCount > 0 ? $qtyRemainCount : 0;
                    $qtyOver = $qtyOverCount > 0 ? $qtyOverCount : 0;

                    if($qtyRemain > 0){$isClosePo += 1;}
                    $item = ReceivingProductSupplierItem::create([
                        'po_material_item_id'       => $request->po_material_item_id[$i],
                        'rec_prod_supplier_id'      => $rec_prod_supplier_id,
                        'warehouse_id'              => $request->warehouse_id[$i],
                        'qty_in'                    => $request->qty_in[$i],
                        'qty_remain'                => $qtyRemain,
                        'qty_over'                  => $qtyOver,
                    ]);
                    if($item){
                        $this->objStock->plusStockSupplier($request->product_supplier_id[$i], $item->warehouse_id, $item->qty_in, "Add receiving product supplier. (".$recProduct->rec_number.")");
                    }
                }

                if($isClosePo == 0){
                    $pos = PurchaseOrderMaterial::find($request->po_material_id);
                    if ($pos){
                        $pos->is_open = 0;
                        $pos->update();
                    }
                }
                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'Add receiving product supplier success.'], 200);
            }

        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }

    public function getTotalReceive($po_material_item_id){
        $qty_in = ReceivingProductSupplierItem::where('po_material_item_id', $po_material_item_id)
                    ->groupBy('po_material_item_id')
                    ->selectRaw('sum(qty_in) as qty_in')
                    ->get();
        return['data'=> $qty_in];
    }

    public function deleteRecProduct($rec_prod_supplier_id){
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }


        DB::beginTransaction();
        try {

            $rec = ReceivingProductSupplier::where('rec_prod_supplier_id', $rec_prod_supplier_id)
                    ->with('items')->get()->first();
            if ($rec){
                $rec->is_active = 0;
                $rec->update();

                foreach ($rec->items as $ls){
                    $item = ReceivingProductSupplierItem::where('rec_prod_supplier_item_id', $ls->rec_prod_supplier_item_id)
                            ->with('poItem', 'poItem.productSupplier')->get()->first();
                    if($item){
                        //$this->objStock->minStockPartSupplier($item->po_item->part_supplier_id, $item->warehouse_id, $item->qty_in, "Cancel Receiving Part. (".$part->recpart_number.")");
                        $this->objStock->minStockSupplier($item->poItem->product_supplier_id, $item->warehouse_id, $item->qty_in, $rec->rec_number);

                    }
                }

                $pos = PurchaseOrderMaterial::find($rec->po_material_id);
                if ($pos){
                    $pos->is_open = 0;
                    $pos->update();
                }

                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'delete receiving part success.'], 200);
            } else {
                DB::rollback();
                return response()->json(['status' => 'Info', 'message' => 'delete receiving part failed.'], 200);
            }

        } catch (Throwable $e){
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }

    }
}
