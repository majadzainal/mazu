<?php

namespace App\Http\Controllers\MazuProcess;

use Throwable;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\MazuMaster\Warehouse;
use Illuminate\Support\Facades\Auth;
use App\Models\MazuMaster\LabelProduct;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\MazuProcess\ReceivingProduct;
use App\Models\MazuProcess\ReceivingProductItem;
use App\Models\MazuProcess\PurchaseOrderSupplier;
use App\Http\Controllers\Log\LogPartStockController;
use App\Http\Controllers\MazuMaster\StockController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Setting\NumberingFormController;

class ReceivingProductController extends Controller
{
    public $MenuID = '081';
    public $objStock;
    public $objNumberingForm;
    public $generateType = 'F_RECEIVING_PRODUCT';

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
        $poList = PurchaseOrderSupplier::where('is_open', 1)
                    ->where('store_id', $store_id)
                    ->where('is_active', 1)
                    ->with('items', 'supplier', 'items.product', 'items.product.unit', 'items.product.stockWarehouse', 'items.product.stockWarehouse.warehouse')
                    ->get();

        // dd($poList);
        $warehouseList = Warehouse::where('is_active', 1)->where('store_id', $store_id)->get();
        return view('mazuprocess.receivingProductTable', [
            'MenuID' => $this->MenuID,
            'poList' => $poList,
            'warehouseList' => $warehouseList,
        ]);

    }

    public function loadRecProduct($start_date, $end_date){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $recProductList = ReceivingProduct::where('is_active', 1)
                                        ->with(["poSupplier" => function($q, $start_date, $end_date){
                                            $q->whereBetween('po_date', [$start_date, $end_date]);
                                        }])
                                        ->with('poSupplier', 'poSupplier.supplier')
                                        ->get();

        return['data'=> $recProductList];
    }

    public function addRecProduct(Request $request){
        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        DB::beginTransaction();
        try {

            $receiving_product_id = Uuid::uuid4()->toString();
            $receiving_product_number = $this->objNumberingForm->GENERATE_FORM_NUMBER($this->generateType);
            $recProduct = ReceivingProduct::create([
                'receiving_product_id'       => $receiving_product_id,
                'receiving_product_number'            => $receiving_product_number,
                'po_supplier_id'                     =>  $request->po_supplier_id,
                'do_number_supplier'        => $request->do_number_supplier,
                'delivered_by'              => $request->delivered_by,
                'received_by'               => $request->received_by,
                'date_receive'              => $request->date_receive,
                'is_manually'                => $request->is_manually,
                'created_user'              => Auth::User()->employee->employee_name,
                'is_manually'               => $request->is_manually,
                'is_active'                 => 1,
            ]);

            $isClosePo = 0;

            if($recProduct){
                for ($i=0; $i<count($request->po_supplier_item_id); $i++ ){
                    $qtyRemainCount = $request->qty[$i] - ($request->qty_in[$i] + $request->qty_received[$i]);
                    $qtyOverCount = ($request->qty_in[$i] + $request->qty_received[$i]) - $request->qty[$i];
                    $qtyRemain = $qtyRemainCount > 0 ? $qtyRemainCount : 0;
                    $qtyOver = $qtyOverCount > 0 ? $qtyOverCount : 0;

                    if($qtyRemain > 0){$isClosePo += 1;}
                    $item = ReceivingProductItem::create([
                        'po_supplier_item_id'             => $request->po_supplier_item_id[$i],
                        'receiving_product_id'      => $receiving_product_id,
                        'warehouse_id'              => $request->warehouse_id[$i],
                        'qty_in'                    => $request->qty_in[$i],
                        'qty_remain'                => $qtyRemain,
                        'qty_over'                  => $qtyOver,
                        'product_label_list'        => $request->product_label_list[$i],
                    ]);
                    if($item){
                        $this->objStock->plusStock($request->product_id[$i], $item->warehouse_id, $item->qty_in, "Add receiving product. (".$recProduct->recpart_number.")");
                    }
                }

                if($isClosePo == 0){
                    $pos = PurchaseOrderSupplier::find($request->po_supplier_id);
                    if ($pos){
                        $pos->is_open = 0;
                        $pos->update();
                    }
                }
                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'Add receiving product success.'], 200);
            }

        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }

    public function getTotalReceive($po_supplier_item_id){
        $qty_in = ReceivingProductItem::where('po_supplier_item_id', $po_supplier_item_id)
                    ->groupBy('po_supplier_item_id')
                    ->selectRaw('sum(qty_in) as qty_in')
                    ->get();
        return['data'=> $qty_in];
    }

    function getProductLabel($product_label){
        $data = LabelProduct::where('no_label', strtoupper($product_label))
                        ->where('is_print', 1)
                        ->where('is_checked_in', 0)
                        ->get()->first();

        return['data'=> $data];
    }

    public function deleteRecProduct($receiving_product_id){
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }


        DB::beginTransaction();
        try {

            $product = ReceivingProduct::where('receiving_product_id', $receiving_product_id)
                    ->with('items')->get()->first();
            if ($product){
                $product->is_active = 0;
                $product->update();

                foreach ($product->items as $ls){
                    $item = ReceivingProductItem::where('receiving_product_item_id', $ls->receiving_product_item_id)
                            ->with('poItem', 'poItem.product')->get()->first();
                    if($item){
                        //$this->objStock->minStockPartSupplier($item->po_item->part_supplier_id, $item->warehouse_id, $item->qty_in, "Cancel Receiving Part. (".$part->recpart_number.")");
                        $this->objStock->minStock($item->poItem->product_id, $item->warehouse_id, $item->qty_in, $product->receiving_product_number);

                    }
                }

                $pos = PurchaseOrderSupplier::find($product->po_supplier_id);
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
