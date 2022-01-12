<?php

namespace App\Http\Controllers\Production;

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
use App\Http\Controllers\Log\LogRequestRawmatController;
use App\Models\Production\RequestRawmat;
use App\Models\Production\RequestRawmatItem;
use App\Models\Master\Plant;
use App\Models\Production\ProductionSchedule;
use App\Models\Part\PartCustomer;
use App\Models\Part\PartSupplier;
use App\Http\Controllers\Part\StockController;
use PDF;
use Illuminate\Support\Facades\File;
use App\Mail\sendMail;
use Illuminate\Support\Facades\Mail;

class RequestRawmatController extends Controller
{
    public $MenuID = '0902';
    public $objNumberingForm;
    public $generateType = 'REQUEST_RAWMAT';
    public $objLogReqRawMat;
    public $objStock;

    public function __construct()
    {
        $this->objLogReqRawMat = new LogRequestRawmatController();
        $this->objNumberingForm = new NumberingFormController();
        $this->objStock = new StockController();
    }

    public function listRequestRawmat(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        $plantList = Plant::where('is_active', 1)->get();

        return view('production.requestRawmatTable', [
            'MenuID'          => $this->MenuID,
            'plantList'       => $plantList,
        ]);
    }

    public function loadRequestRawmat($month_periode, $year_periode){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $date = date($year_periode."-".$month_periode."-1");
        $datefrom = date("Y-m-1", strtotime($date));
        $dateto = date("Y-m-t", strtotime($date));

        $requestList = RequestRawmat::where('is_active', 1)
                                        ->whereBetween('request_date', [$datefrom, $dateto])
                                        ->with('plant', 'request_item')
                                        ->get();

        return['data'=> $requestList];
    }

    function getDataRawMaterial($plant, $date){
        $part = PartCustomer::where('plant_id', $plant)->pluck('part_customer_id');

        $partProductionList = ProductionSchedule::where('schedule_date', $date)
                    ->whereIn('part_customer_id', $part)
                    ->with('part_customer')
                    ->get();

        $rawMatList = DB::table('tp_production_schedule as ps')
                    ->groupBy('bmi.part_id', 'bmi.price')
                    ->select('bmi.part_id', DB::raw('SUM(ps.qty * bmi.amount_usage) as qty'), 'bmi.price')
                    ->leftJoin('tm_bill_material as bm', 'ps.part_customer_id', '=', 'bm.part_customer_id')
                    ->leftJoin('tm_bill_material_item as bmi', 'bm.bill_material_id', '=', 'bmi.bill_material_id')
                    ->where('ps.schedule_date', $date)
                    ->whereIn('ps.part_customer_id', $part)
                    ->orderBy('bmi.part_id', 'ASC')->get();


        $partid = $rawMatList->pluck('part_id');
        $partSupplierList = PartSupplier::select('*', 'part_supplier_id as part_id')
                                ->whereIn('part_supplier_id', $partid)->with('unit', 'stock_warehouse', 'stock_warehouse.warehouse', 'part_price_active')
                                ->get();

        $partCustomerList = PartCustomer::select('*', 'part_customer_id as part_id')
                                ->whereIn('part_customer_id', $partid)->with('unit', 'stock_warehouse', 'stock_warehouse.warehouse', 'part_price_active')
                                ->get();

        $partList = $partSupplierList->merge($partCustomerList);
        //return['data'=> $partList];
        return['data'=> ['rawMatList'=> $rawMatList,
                         'partList'=> $partList,
                         'partProductionList' => $partProductionList
                        ]
                ];
    }

    public function addRequestRawmat(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        $isExist = RequestRawmat::where('plant_id', $request->plant_id)->where('request_date', $request->request_date)->where('is_active', 1)->count();
        if ($isExist > 0)
            return response()->json(['status' => 'Info', 'message' => 'Plant and Request Date already exists'], 202);

        DB::beginTransaction();
        try {

            $arrsuccess = 0;

            $request_id = Uuid::uuid4()->toString();
            $request_number = $this->objNumberingForm->GENERATE_FORM_NUMBER($this->generateType);
            $requestRawMat = RequestRawmat::create([
                'request_id'               => $request_id,
                'request_number'           => $request_number,
                'plant_id'                 => $request->plant_id,
                'request_date'             => $request->request_date,
                'description'              => $request->note,
                'is_active'                => 1,
                'is_status'                => $request->is_status,
                'created_user'             => Auth::User()->employee->employee_name,
            ]);

            if($requestRawMat){

                for ($i=0; $i<count($request->part_id); $i++ ){
                    $item = RequestRawmatItem::create([
                        'part_id'               => $request->part_id[$i],
                        'request_id'            => $request_id,
                        'qty'                   => $request->qty[$i],
                        'warehouse_id'          => $request->warehouse[$i],
                        'unit'                  => $request->unit[$i],
                        'price'                 => $request->price[$i],
                        'note'                  => $request->note_material[$i]
                    ]);

                    if($item){
                        $partSupplierExist = PartSupplier::where('part_supplier_id', $item->part_id)->exists();
                        if ($partSupplierExist)
                            $this->objStock->minStockPartSupplier($request->part_id[$i], $request->warehouse[$i], $request->qty[$i], "Request Raw Material. (".$request_id.")");
                        else
                            $this->objStock->minStockPartCustomer($request->part_id[$i], $request->warehouse[$i], $request->qty[$i], "Request Raw Material. (".$request_id.")");

                        $arrsuccess++;
                    }
                }

                $this->objLogReqRawMat->addLogRequestRawmat($request_id, $request->is_status, '');

                if (count($request->part_id) == $arrsuccess ){
                    DB::commit();
                    return response()->json(['status' => 'Success', 'message' => 'Add Request Raw Material success.'], 200);
                } else {
                    DB::rollback();
                    return response()->json(['status' => 'Error', 'message' => 'Add Request Raw Material, with a part error.' ], 202);
                }
            }

        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }

    }


    function updateRequestRawmat(Request $request){
        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        if ($request->plant_id != $request->plant || $request->request_date != $request->rdate){
            $isExist = RequestRawmat::where('plant_id', $request->plant_id)->where('request_date', $request->request_date)->where('is_active', 1)->count();
            if ($isExist > 0)
                return response()->json(['status' => 'Info', 'message' => 'Plant and Request Date already exists'], 200);
        }


        DB::beginTransaction();
        try {
            $arrsuccess = 0;
            $requestRawMat = RequestRawmat::find($request->request_id);
            if($requestRawMat){

                $requestRawMat->update([
                    'plant_id'                 => $request->plant_id,
                    'request_date'             => $request->request_date,
                    'description'              => $request->note,
                    'is_status'                => $request->is_status,
                    'updated_user'             => Auth::User()->employee->employee_name,
                ]);

                $itemReq = RequestRawmatItem::where('request_id', $request->request_id)->get();
                foreach($itemReq as $item){
                    $partSupplierExist = PartSupplier::where('part_supplier_id', $item->part_id)->exists();
                    if ($partSupplierExist)
                        $this->objStock->cancelStockPartSupplierRequestRawMat($item->part_id, $item->warehouse_id, $item->qty, $item->request_id);
                    else
                        $this->objStock->cancelStockPartCustomerRequestRawMat($item->part_id, $item->warehouse_id, $item->qty, $item->request_id);
                }

                RequestRawmatItem::where('request_id', $request->request_id)->delete();

                for ($i=0; $i<count($request->part_id); $i++ ){
                    $item = RequestRawmatItem::create([
                        'part_id'               => $request->part_id[$i],
                        'request_id'            => $request->request_id,
                        'qty'                   => $request->qty[$i],
                        'warehouse_id'          => $request->warehouse[$i],
                        'unit'                  => $request->unit[$i],
                        'price'                 => $request->price[$i],
                        'note'                  => $request->note_material[$i]
                    ]);

                    if($item){

                        $partSupplierExist = PartSupplier::where('part_supplier_id', $item->part_id)->exists();
                        if ($partSupplierExist)
                            $this->objStock->minStockPartSupplier($request->part_id[$i], $request->warehouse[$i], $request->qty[$i], "Request Raw Material. (".$request->request_id.")");
                        else
                            $this->objStock->minStockPartCustomer($request->part_id[$i], $request->warehouse[$i], $request->qty[$i], "Request Raw Material. (".$request->request_id.")");

                        $arrsuccess++;
                    }
                }

                $this->objLogReqRawMat->addLogRequestRawmat($request->request_id, $request->is_status, '');
                if (count($request->part_id) == $arrsuccess){
                    DB::commit();
                    return response()->json(['status' => 'Success', 'message' => 'Update Request Raw Material success.'], 200);
                } else {
                    DB::rollback();
                    return response()->json(['status' => 'Error', 'message' => 'Update Request Raw Material failled, with a part error.' ], 202);
                }
            } else {
                DB::rollback();
                return response()->json(['status' => 'Error', 'message' => 'Update Request Raw Material failled' ], 202);
            }

        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }

    function deleteRequestRawmat($request_id){
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        DB::beginTransaction();
        try {

            $rq = RequestRawmat::find($request_id);
            if ($rq){
                $rq->is_active = 0;
                $rq->update();

                if ($rq){
                    $itemReq = RequestRawmatItem::where('request_id', $request_id)->get();
                    foreach($itemReq as $item){
                        $partSupplierExist = PartSupplier::where('part_supplier_id', $item->part_id)->exists();
                        if ($partSupplierExist)
                            $this->objStock->cancelStockPartSupplierRequestRawMat($item->part_id, $item->warehouse_id, $item->qty, $item->request_id);
                        else
                            $this->objStock->cancelStockPartCustomerRequestRawMat($item->part_id, $item->warehouse_id, $item->qty, $item->request_id);
                    }
                    DB::commit();
                    return response()->json(['status' => 'Success', 'message' => 'delete Request Raw Material success.'], 200);
                }

            } else {
                DB::rollback();
                return response()->json(['status' => 'Info', 'message' => 'delete Request Raw Material failed.'], 200);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }

    public $MenuIDKnown = "0903";
    public function listKnownRequestRawmat(){

        if(!isAccess('read', $this->MenuIDKnown)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuIDKnown,
            ]);
        }

        $plantList = Plant::where('is_active', 1)->get();
        return view('production.requestRawmatKnown', [
            'MenuID'          => $this->MenuIDKnown,
            'plantList'       => $plantList,
        ]);
    }

    public function loadKnownRequestRawmat($month_periode, $year_periode){
        if(!isAccess('read', $this->MenuIDKnown)){
            return['data'=> ''];
        }

        $date = date($year_periode."-".$month_periode."-1");
        $datefrom = date("Y-m-1", strtotime($date));
        $dateto = date("Y-m-t", strtotime($date));

        $requestList = RequestRawmat::where('is_active', 1)
                                        ->where('is_status', 2)
                                        ->whereBetween('request_date', [$datefrom, $dateto])
                                        ->with('plant', 'request_item')
                                        ->get();

        return['data'=> $requestList];
    }

    function updateKnownRequestRawmat(Request $request){
        if(!isAccess('update', $this->MenuIDKnown)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        DB::beginTransaction();
        try {
            $arrsuccess = 0;
            $requestRawMat = RequestRawmat::find($request->request_id);
            if($requestRawMat){

                $requestRawMat->update([
                    'is_status'                => 3,
                    'updated_user'             => Auth::User()->employee->employee_name,
                ]);

                $this->objLogReqRawMat->addLogRequestRawmat($request->request_id, 3, $request->note_log);
                if ($requestRawMat){
                    DB::commit();
                    return response()->json(['status' => 'Success', 'message' => 'Process Request Raw Material success.'], 200);
                } else {
                    DB::rollback();
                    return response()->json(['status' => 'Error', 'message' => 'Process Request Raw Material failled' ], 202);
                }
            } else {
                DB::rollback();
                return response()->json(['status' => 'Error', 'message' => 'Process Request Raw Material failled' ], 202);
            }

        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }

    public $MenuIDApprove = "0904";
    public function listApproveRequestRawmat(){

        if(!isAccess('read', $this->MenuIDApprove)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuIDApprove,
            ]);
        }

        $plantList = Plant::where('is_active', 1)->get();
        return view('production.requestRawmatApprove', [
            'MenuID'          => $this->MenuIDApprove,
            'plantList'       => $plantList,
        ]);
    }

    public function loadApproveRequestRawmat($month_periode, $year_periode){
        if(!isAccess('read', $this->MenuIDApprove)){
            return['data'=> ''];
        }

        $date = date($year_periode."-".$month_periode."-1");
        $datefrom = date("Y-m-1", strtotime($date));
        $dateto = date("Y-m-t", strtotime($date));

        $requestList = RequestRawmat::where('is_active', 1)
                                        ->where('is_status', 3)
                                        ->whereBetween('request_date', [$datefrom, $dateto])
                                        ->with('plant', 'request_item')
                                        ->get();

        return['data'=> $requestList];
    }

    function updateApproveRequestRawmat(Request $request){
        if(!isAccess('update', $this->MenuIDApprove)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        DB::beginTransaction();
        try {
            $arrsuccess = 0;
            $requestRawMat = RequestRawmat::find($request->request_id);
            if($requestRawMat){

                $requestRawMat->update([
                    'is_status'                => $request->is_status,
                    'updated_user'             => Auth::User()->employee->employee_name,
                ]);

                $this->objLogReqRawMat->addLogRequestRawmat($request->request_id, $request->is_status, $request->note_log);
                if ($requestRawMat){
                    DB::commit();
                    return response()->json(['status' => 'Success', 'message' => 'Process Request Raw Material success.'], 200);
                } else {
                    DB::rollback();
                    return response()->json(['status' => 'Error', 'message' => 'Process Request Raw Material failled' ], 202);
                }
            } else {
                DB::rollback();
                return response()->json(['status' => 'Error', 'message' => 'Process Request Raw Material failled' ], 202);
            }

        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }

    public function printRequestRawmat($req_id){

        if(!isAccess('read', $this->MenuID)){
            return "You do not have access for this action";
        }

        $req = RequestRawmat::with('plant', 'request_item', 'request_item.part_supplier', 'request_item.part_customer', 'request_item.warehouse', 'request_item.units')
                            ->with('log_request', function ($query){
                                $query->orderBy('created_at', 'DESC');
                            })
                            ->where('request_id', $req_id)->first();
        if($req){
            $data = ['req'  => $req];
            $pdf = PDF::loadView('production.printRequestRawmat', $data);
            $pdf->setPaper('A4', 'potrait');
            return $pdf->stream('REQUEST_'.$req->request_number.'.pdf');
        } else {
            return "Data not found";
        }

    }

    public function sendRequestRawmat(Request $request){

        $path = public_path('assets/tempFile/');

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        $req = RequestRawmat::with('plant', 'request_item', 'request_item.part_supplier', 'request_item.part_customer', 'request_item.warehouse', 'request_item.units')
                            ->with('log_request', function ($query){
                                $query->orderBy('created_at', 'DESC');
                            })
                            ->where('request_id', $request->req_id)->first();

        if($req){

            $data = ['req'  => $req];
            $pdf = PDF::loadView('production.printRequestRawmat', $data);
            $pdf->setPaper('A4', 'potrait');
            $pdf->save($path.'REQUEST_'.$req->request_number.'.pdf');

            $mailto = explode(",", $request->to);
            $mailcc = explode(",", $request->cc);

            $objMail = new \stdClass();
            $objMail->subject = $request->subject;
            $objMail->desc = $request->desc;
            $objMail->attach = 'REQUEST_'.$req->request_number.'.pdf';

            $email = Mail::to($mailto)
                ->cc($mailcc)
                ->send(new sendMail($objMail));

            $this->updateSendRequestRawmat($request, 5);

            if (Mail::failures()) {
                return response()->json(['status' => 'Info', 'message' => 'send Request Raw Material failed.'], 200);
            } else {
                File::delete($path.'REQUEST_'.$req->request_number.'.pdf');
                return response()->json(['status' => 'Success', 'message' => 'send Request Raw Material success.'], 200);
            }

        } else {
            return response()->json(['status' => 'Info', 'message' => 'Request Raw Material not found.'], 200);
        }
    }

    function updateSendRequestRawmat($request, $status){

        try {
            $requestRawMat = RequestRawmat::find($request->req_id);
            if($requestRawMat){

                $requestRawMat->update([
                    'is_status'                => $status,
                    'updated_user'             => Auth::User()->employee->employee_name,
                ]);

                $this->objLogReqRawMat->addLogRequestRawmat($request->req_id, $status, 'Subject : '.$request->subject.' Description : '.$request->desc);
            }
        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }
}
