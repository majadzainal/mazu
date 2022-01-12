<?php

namespace App\Http\Controllers\Process;

use Throwable;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Setting\NumberingFormController;
use App\Http\Controllers\Log\LogPOController;
use App\Models\Master\Supplier;
use App\Models\Part\PartSupplier;
use App\Models\Part\PartCustomer;
use App\Models\Process\PurchaseOrder;
use App\Models\Process\POItem;

class AdjustmentPurchaseOrderController extends Controller
{
    public $MenuID = '0603';
    public $objForecast;

    public function __construct()
    {
        $this->objLogPO = new LogPOController();
    }

    public function listAdjustPO(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        $supplierList = Supplier::where('is_active', 1)->get();
        //$partCustomerIsSupplierList = PartCustomer::where('is_active', 1)->where('is_supplier', 1)->with('customer', 'part_price', 'divisi', 'unit')->get();
        //$partSupplierList = PartSupplier::where('is_active', 1)->with('supplier', 'part_price', 'divisi', 'unit')->get();
        //$partList = $partSupplierList->merge($partCustomerIsSupplierList);
        return view('process.adjustmentPurchaseOrderTable', [
            'MenuID'            => $this->MenuID,
            'supplierList'      => $supplierList,
        ]);
    }

    public function loadAdjustPO($month_periode, $year_periode){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }


        $POList = PurchaseOrder::where('is_active', 1)
                                        ->where('month_periode', $month_periode)
                                        ->where('year_periode', $year_periode)
                                        ->whereIn('status_process', array(2, 3, 4))
                                        ->with('supplier', 'status_po')
                                        ->get();

        return['data'=> $POList];
    }

    function getAdjustPO($po_id){
        $PO = PurchaseOrder::with('supplier', 'po_items', 'po_items.partSupplier', 'po_items.partSupplier.unit', 'po_items.partSupplier.divisi', 'log_po', 'log_po.log_status')->where('po_id', $po_id)->get()->first();

        return['data'=> $PO];
    }

    function updateAdjustPO(Request $request){
        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        DB::beginTransaction();
        try {
            $arrsuccess = 0;
            $po = PurchaseOrder::find($request->po_id);
            if($po){

                $po->update([
                    'price'               => $request->Htotal,
                    'ppn'                 => $request->Hppn,
                    'total_price'         => $request->Htotal_ppn,
                    'status_process'      => $request->status_process,
                    'is_open'             => 1,
                    'is_active'           => 1,
                    'updated_user'        => Auth::User()->employee->employee_name,
                ]);

                for ($i=0; $i<count($request->poitem_id); $i++ ){
                    $item = POItem::find($request->poitem_id[$i]);
                    if($item){
                        $item->update([
                            'qty'                   => $request->qty[$i],
                            'qty_ng_rate'           => $request->ng_rate[$i],
                            'buffer_stock'          => $request->buffer_stock[$i],
                            'stock'                 => $request->stock[$i],
                            'order'                 => $request->order[$i],
                            'price'                 => $request->price[$i],
                            'total_price'           => $request->total_ppn[$i],
                            'is_active'             => 1,
                        ]);
                    }

                    if($item){
                        $arrsuccess++;
                    }
                }

                $this->objLogPO->addLogPO($request->po_id, $request->status_process, $request->note);

                if (count($request->poitem_id) == $arrsuccess){
                    DB::commit();
                    return response()->json(['status' => 'Success', 'message' => 'Update purchase order success.'], 200);
                } else {
                    DB::rollback();
                    return response()->json(['status' => 'Error', 'message' => 'Update purchase order failled, with a part error.' ], 202);
                }
            } else {
                DB::rollback();
                return response()->json(['status' => 'Error', 'message' => 'Update purchase order failled' ], 202);
            }

        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }

}
