<?php

namespace App\Http\Controllers\Process;

use Throwable;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Models\Master\Warehouse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Process\PurchaseOrder;
use App\Models\Process\RecPartSupplier;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Process\RecPartSupplierItem;
use App\Http\Controllers\Part\StockController;
use App\Http\Controllers\Log\LogPartStockController;
use App\Http\Controllers\Part\PartSupplierController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Process\PurchaseOrderController;
use App\Http\Controllers\Setting\NumberingFormController;

class RecPartSupplierController extends Controller
{
    public $MenuID = '07';
    public $objStock;
    public $objLogPartStock;
    public $objPurchaseOrder;
    public $objPartSupplier;
    public $objNumberingForm;
    public $generateType = 'F_RECEIVING_PART_SUPPLIER';

    public function __construct()
    {
        $this->objStock = new StockController();
        $this->objLogPartStock = new LogPartStockController();
        $this->objPurchaseOrder = new PurchaseOrderController();
        $this->objNumberingForm = new NumberingFormController();
        $this->objPartSupplier = new PartSupplierController();
    }
    public function listRecPartSupplier(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        $poList = PurchaseOrder::where('status_process', 6)
                    ->where('is_open', 1)
                    ->where('is_active', 1)
                    ->with('po_items', 'supplier')
                    ->get();
        $warehouseList = Warehouse::where('is_active', 1)->get();
        return view('process.recpartSupplierTable', [
            'MenuID' => $this->MenuID,
            'poList' => $poList,
            'warehouseList' => $warehouseList,
        ]);

    }

    public function loadRecPartSupplier($month_periode, $year_periode){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $recPartSupplierList = RecPartSupplier::where('is_active', 1)
                                        ->with(["po" => function($q, $month_periode, $year_periode){
                                            $q->where('po.month_periode', $month_periode);
                                            $q->where('po.year_periode', $year_periode);
                                        }])
                                        ->with('po', 'po.supplier', 'recpart_item', 'recpart_item.warehouse', 'recpart_item.po_item', 'recpart_item.po_item.partSupplier')
                                        ->get();

        return['data'=> $recPartSupplierList];
    }

    public function addRecPartSupplier(Request $request){
        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        DB::beginTransaction();
        try {

            $recpart_supplier_id = Uuid::uuid4()->toString();
            $recpart_number = $this->objNumberingForm->GENERATE_FORM_NUMBER($this->generateType);
            $recPart = RecPartSupplier::create([
                'recpart_supplier_id'       => $recpart_supplier_id,
                'recpart_number'            => $recpart_number,
                'po_id'                     =>  $request->po_id,
                'do_number_supplier'        => $request->do_number_supplier,
                'delivered_by'              => $request->delivered_by,
                'received_by'               => $request->received_by,
                'date_receive'              => $request->date_receive,
                'created_user'              => Auth::User()->employee->employee_name,
                'is_manually'               => $request->is_manually,
                'is_active'                 => 1,
            ]);

            $isClosePo = 0;

            if($recPart){
                for ($i=0; $i<count($request->poitem_id); $i++ ){
                    $qtyRemainCount = $request->qty[$i] - ($request->qty_in[$i] + $request->qty_received[$i]);
                    $qtyOverCount = ($request->qty_in[$i] + $request->qty_received[$i]) - $request->qty[$i];
                    $qtyRemain = $qtyRemainCount > 0 ? $qtyRemainCount : 0;
                    $qtyOver = $qtyOverCount > 0 ? $qtyOverCount : 0;

                    if($qtyRemain > 0){$isClosePo += 1;}
                    $item = RecPartSupplierItem::create([
                        'poitem_id'             => $request->poitem_id[$i],
                        'recpart_supplier_id'   => $recpart_supplier_id,
                        'warehouse_id'          => $request->warehouse_id[$i],
                        'qty_in'                => $request->qty_in[$i],
                        'qty_remain'            => $qtyRemain,
                        'qty_over'              => $qtyOver,
                        'part_label_list'       => $request->part_label[$i],
                        'is_active'             => 1,
                    ]);
                    if($item){
                        $this->objStock->plusStockPartSupplier($request->part_supplier_id[$i], $item->warehouse_id, $item->qty_in, "Add Receiving Part. (".$recPart->recpart_number.")");
                    }
                }

                if($isClosePo == 0){
                    $this->objPurchaseOrder->closePurchaseOrder($recPart->po_id);
                }
                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'Add purchase order success.'], 200);
            }

        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }

    public function deleteRecPartSupplier($recpart_supplier_id){
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }


        DB::beginTransaction();
        try {

            $part = RecPartSupplier::where('recpart_supplier_id', $recpart_supplier_id)
                    ->with('recpart_item')->get()->first();
            if ($part){
                $part->is_active = 0;
                $part->update();

                foreach ($part->recpart_item as $ls){
                    $item = RecPartSupplierItem::where('recpart_item_id', $ls->recpart_item_id)
                            ->with('po_item', 'po_item.partSupplier')->get()->first();
                    $item->is_active = 0;
                    $item->update();
                    if($item){
                        $this->objStock->minStockPartSupplier($item->po_item->part_supplier_id, $item->warehouse_id, $item->qty_in, "Cancel Receiving Part. (".$part->recpart_number.")");

                    }
                }

                $this->objPurchaseOrder->openPurchaseOrder($part->po_id);

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

    public function getPoByPeriode($month_periode, $year_periode)
    {
        $poList = PurchaseOrder::where('status_process', 6)
                    ->where('is_open', 1)
                    ->where('is_active', 1)
                    ->where('month_periode', $month_periode)
                    ->where('year_periode', $year_periode)
                    ->with('po_items', 'po_items.partSupplier', 'supplier')
                    ->get();

        return['data'=> $poList];
    }

    public function getTotalReceive($poitem_id){
        $qty_in = RecPartSupplierItem::where('poitem_id', $poitem_id)
                    ->groupBy('poitem_id')
                    ->selectRaw('sum(qty_in) as qty_in')
                    ->where('is_active', 1)
                    ->get();

        return['data'=> $qty_in];
    }


}
