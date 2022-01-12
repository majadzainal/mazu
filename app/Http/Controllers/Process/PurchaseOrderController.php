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
use Illuminate\Support\Facades\File;
use App\Mail\sendMail;
use Illuminate\Support\Facades\Mail;
use PDF;

class PurchaseOrderController extends Controller
{
    public $MenuID = '0601';
    public $objNumberingForm;
    public $objForecast;
    public $generateType = 'F_PURCHASE_ORDER';

    public function __construct()
    {
        $this->objNumberingForm = new NumberingFormController();
        $this->objLogPO = new LogPOController();
    }

    public function listPurchaseOrder(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        $supplierList = Supplier::where('is_active', 1)->get();
        $partCustomerIsSupplierList = PartCustomer::where('is_active', 1)->where('is_supplier', 1)->with('customer', 'part_price', 'divisi', 'unit')->get();
        $partSupplierList = PartSupplier::where('is_active', 1)->with('supplier', 'part_price', 'divisi', 'unit')->get();
        $partList = $partSupplierList->merge($partCustomerIsSupplierList);

        return view('process.purchaseOrderTable', [
            'MenuID'            => $this->MenuID,
            'supplierList'      => $supplierList,
            'partList'          => $partList,
        ]);
    }

    public function loadPurchaseOrder($month_periode, $year_periode){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $purchaseOrderList = PurchaseOrder::where('is_active', 1)
                                        ->where('additional', 0)
                                        ->where('month_periode', $month_periode)
                                        ->where('year_periode', $year_periode)
                                        ->with('supplier', 'status_po')
                                        ->get();

        return['data'=> $purchaseOrderList];
    }

    function getPurchaseOrder($po_id){
        $PO = PurchaseOrder::with('supplier', 'po_items', 'log_po', 'log_po.log_status')->where('po_id', $po_id)->get()->first();

        return['data'=> $PO];
    }

    public function addPurchaseOrder(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        DB::beginTransaction();
        try {

            $arrsuccess = 0;

            $po_id = Uuid::uuid4()->toString();
            $po_number = $this->objNumberingForm->GENERATE_FORM_NUMBER($this->generateType);
            $po = PurchaseOrder::create([
                'po_id'               => $po_id,
                'po_number'           => $po_number,
                'supplier_id'         => $request->supplier_id,
                'po_date'             => $request->po_date,
                'month_periode'       => $request->month_periode,
                'year_periode'        => $request->year_periode,
                'price'               => $request->Htotal,
                'ppn'                 => $request->Hppn,
                'total_price'         => $request->Htotal_ppn,
                'status_process'      => $request->status_process,
                'additional'          => 0,
                'is_open'             => 1,
                'is_active'           => 1,
                'created_user'        => Auth::User()->employee->employee_name,
            ]);


            if($po){

                for ($i=0; $i<count($request->part_id); $i++ ){
                    $item = POItem::create([
                        'part_supplier_id'      => $request->part_id[$i],
                        'po_id'                 => $po_id,
                        'qty'                   => $request->qty[$i],
                        'qty_ng_rate'           => $request->ng_rate[$i],
                        'buffer_stock'          => $request->buffer_stock[$i],
                        'stock'                 => $request->stock[$i],
                        'order'                 => $request->order[$i],
                        'price'                 => $request->price[$i],
                        'total_price'           => $request->total_ppn[$i],
                        'is_active'             => 1,
                    ]);

                    if($item){
                        $arrsuccess++;
                    }
                }

                $this->objLogPO->addLogPO($po_id, $request->status_process, '');

                if (count($request->part_id) == $arrsuccess ){
                    DB::commit();
                    return response()->json(['status' => 'Success', 'message' => 'Add purchase order success.'], 200);
                } else {
                    DB::rollback();
                    return response()->json(['status' => 'Error', 'message' => 'Add purchase order failled, with a part error.' ], 202);
                }
            }

        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }

    }



    function updatePurchaseOrder(Request $request){
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
                    'supplier_id'         => $request->supplier_id,
                    'po_date'             => $request->po_date,
                    'month_periode'       => $request->month_periode,
                    'year_periode'        => $request->year_periode,
                    'price'               => $request->Htotal,
                    'ppn'                 => $request->Hppn,
                    'total_price'         => $request->Htotal_ppn,
                    'status_process'      => $request->status_process,
                    'is_open'             => 1,
                    'is_active'           => 1,
                    'updated_user'        => Auth::User()->employee->employee_name,
                ]);

                POItem::where('po_id', $request->po_id)->delete();

                for ($i=0; $i<count($request->part_id); $i++ ){
                    $item = POItem::create([
                        'part_supplier_id'      => $request->part_id[$i],
                        'po_id'                 => $request->po_id,
                        'qty'                   => $request->qty[$i],
                        'qty_ng_rate'           => $request->ng_rate[$i],
                        'buffer_stock'          => $request->buffer_stock[$i],
                        'stock'                 => $request->stock[$i],
                        'order'                 => $request->order[$i],
                        'price'                 => $request->price[$i],
                        'total_price'           => $request->total_ppn[$i],
                        'is_active'             => 1,
                    ]);


                    if($item){
                        $arrsuccess++;
                    }
                }

                $this->objLogPO->addLogPO($request->po_id, $request->status_process, 'Edit Puchase Order');

                if (count($request->part_id) == $arrsuccess){
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

    function deletePurchaseOrder($po_id){
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $po = PurchaseOrder::find($po_id);
            if ($po){
                $po->is_active = 0;
                $po->update();

                return response()->json(['status' => 'Success', 'message' => 'delete purchase order success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'delete purchase order failed.'], 200);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }

    function closePurchaseOrder($po_id){
        $po = PurchaseOrder::find($po_id);
        $po->update([
            'is_open'             => 0,
        ]);
    }
    function openPurchaseOrder($po_id){
        $po = PurchaseOrder::find($po_id);
        $po->update([
            'is_open'             => 1,
        ]);
    }

    public function printPurchaseOrder($po_id){

        if(!isAccess('read', $this->MenuID)){
            return "You do not have access for this action";
        }

        $PO = PurchaseOrder::with('supplier', 'po_items', 'po_items.partSupplier', 'po_items.partSupplier.unit')
                            ->with('log_po', function ($query){
                                $query->orderBy('created_at', 'DESC');
                            })
                            ->where('po_id', $po_id)->first();
        if($PO){
            $data = ['po'  => $PO];
            $pdf = PDF::loadView('process.print.purchaseOrder', $data);
            $pdf->setPaper('A4', 'potrait');
            return $pdf->stream('PO_'.$PO->po_number.'.pdf');
        } else {
            return "Data not found";
        }

    }

    public function sendPurchaseOrder(Request $request){

        $path = public_path('assets/tempFile/');

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }

        $PO = PurchaseOrder::with('supplier', 'po_items', 'po_items.partSupplier', 'po_items.partSupplier.unit')
                            ->with('log_po', function ($query){
                                $query->orderBy('created_at', 'DESC');
                            })
                            ->where('po_id', $request->poID)->first();
        if($PO){

            $data = ['po'  => $PO];
            $pdf = PDF::loadView('process.print.purchaseOrder', $data);
            $pdf->setPaper('A4', 'potrait');
            $pdf->save($path.$PO->po_number.'.pdf');

            $mailto = explode(",", $request->to);
            $mailcc = explode(",", $request->cc);

            $objMail = new \stdClass();
            $objMail->subject = $request->subject;
            $objMail->desc = $request->desc;
            $objMail->attach = $PO->po_number.'.pdf';

            $email = Mail::to($mailto)
                ->cc($mailcc)
                ->send(new sendMail($objMail));

            $this->updateStatusPurchaseOrder($request->poID, 6);

            if (Mail::failures()) {
                return response()->json(['status' => 'Info', 'message' => 'send purchase order failed.'], 200);
            } else {
                File::delete($path.$PO->po_number.'.pdf');
                return response()->json(['status' => 'Success', 'message' => 'send purchase order success.'], 200);
            }

        } else {
            return response()->json(['status' => 'Info', 'message' => 'purchase order not found.'], 200);
        }
    }

    function updateStatusPurchaseOrder($po_id, $status){

        try {

            $po = PurchaseOrder::find($po_id);
            if($po){
                $po->update([
                    'status_process'      => $status,
                    'is_open'             => 1,
                    'is_active'           => 1,
                    'updated_user'        => Auth::User()->employee->employee_name,
                ]);

                $this->objLogPO->addLogPO($request->poID, $status, 'Subject : '.$request->subject.' Description : '.$request->desc);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }

}
