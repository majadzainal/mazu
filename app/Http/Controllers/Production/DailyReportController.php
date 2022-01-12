<?php

namespace App\Http\Controllers\Production;

use PDF;
use Throwable;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Mail\sendMail;
use App\Models\Master\Plant;
use Illuminate\Http\Request;
use App\Models\Part\PartCustomer;
use App\Models\Part\PartSupplier;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use App\Models\Production\DailyReport;
use App\Models\Production\RequestRawmat;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Production\DailyReportItem;
use App\Models\Production\ProductionSchedule;
use App\Http\Controllers\Part\StockController;
use App\Http\Controllers\Log\LogRequestRawmatController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Setting\NumberingFormController;


class DailyReportController extends Controller
{
    public $MenuID = '0906';
    public $objNumberingForm;
    public $objLogReqRawMat;
    public $objStock;

    public function __construct()
    {

    }

    public function listDailyReport(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        $plantList = Plant::where('is_active', 1)->get();

        return view('production.dailyReportTable', [
            'MenuID'          => $this->MenuID,
            'plantList'       => $plantList,
        ]);
    }

    public function loadDailyReport($month_periode, $year_periode){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $date = date($year_periode."-".$month_periode."-1");
        $datefrom = date("Y-m-1", strtotime($date));
        $dateto = date("Y-m-t", strtotime($date));

        $reportList = DailyReport::where('is_active', 1)
                                        ->whereBetween('report_date', [$datefrom, $dateto])
                                        ->with('plant', 'report_item', 'report_item.part_customer')
                                        ->get();

        return['data'=> $reportList];
    }

    function getDataDailyReport($plant, $date){
        $part = PartCustomer::where('plant_id', $plant)->pluck('part_customer_id');

        $partProductionList = ProductionSchedule::where('schedule_date', $date)
                    ->whereIn('part_customer_id', $part)
                    ->with('part_customer', 'bom.bom_item.wip')
                    ->get();

        return['data'=> $partProductionList];
    }

    public function addDailyReport(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        $isExist = DailyReport::where('plant_id', $request->plant_id)->where('report_date', $request->report_date)->where('is_active', 1)->count();
        if ($isExist > 0)
            return response()->json(['status' => 'Info', 'message' => 'Plant and Report Date already exists'], 202);

        DB::beginTransaction();
        try {

            $arrsuccess = 0;

            $report_id = Uuid::uuid4()->toString();
            $report = DailyReport::create([
                'report_id'                => $report_id,
                'plant_id'                 => $request->plant_id,
                'report_date'              => $request->report_date,
                'description'              => $request->note,
                'is_active'                => 1,
                'is_status'                => $request->is_status,
                'created_user'             => Auth::User()->employee->employee_name,
            ]);

            if($report){

                for ($i=0; $i<count($request->part_customer_id); $i++ ){
                    $item = DailyReportItem::create([
                        'part_customer_id' => $request->part_customer_id[$i],
                        'report_id'        => $report_id,
                        'production_plan'  => $request->production_plan[$i],
                        'actual'           => $request->actual[$i],
                        'over_time'        => $request->over_time[$i],
                        'total'            => $request->total[$i],
                        'is_wip'           => $request->wip[$i],
                        'reference_id'     => $request->reference_id[$i]
                    ]);

                    if($item){
                        $arrsuccess++;
                    }
                }

                if (count($request->part_customer_id) == $arrsuccess ){
                    DB::commit();
                    return response()->json(['status' => 'Success', 'message' => 'Add Daily Report success.'], 200);
                } else {
                    DB::rollback();
                    return response()->json(['status' => 'Error', 'message' => 'Add Daily Report, with a part error.' ], 202);
                }
            }

        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }

    }


    function updateDailyReport(Request $request){
        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        if ($request->plant_id != $request->plant || $request->report_date != $request->rdate){
            $isExist = DailyReport::where('plant_id', $request->plant_id)->where('report_date', $request->report_date)->where('is_active', 1)->count();
            if ($isExist > 0)
                return response()->json(['status' => 'Info', 'message' => 'Plant and Report Date already exists'], 200);
        }

        DB::beginTransaction();
        try {
            $arrsuccess = 0;
            $report = DailyReport::find($request->report_id);
            if($report){

                $report->update([
                    'plant_id'                 => $request->plant_id,
                    'report_date'              => $request->report_date,
                    'description'              => $request->note,
                    'is_status'                => $request->is_status,
                    'updated_user'             => Auth::User()->employee->employee_name,
                ]);

                DailyReportItem::where('report_id', $request->report_id)->delete();

                for ($i=0; $i<count($request->part_customer_id); $i++ ){
                    $item = DailyReportItem::create([
                        'part_customer_id' => $request->part_customer_id[$i],
                        'report_id'        => $request->report_id,
                        'production_plan'  => $request->production_plan[$i],
                        'actual'           => $request->actual[$i],
                        'over_time'        => $request->over_time[$i],
                        'total'            => $request->total[$i],
                        'is_wip'           => $request->wip[$i],
                        'reference_id'     => $request->reference_id[$i]
                    ]);

                    if($item){
                        $arrsuccess++;
                    }
                }

                if (count($request->part_customer_id) == $arrsuccess){
                    DB::commit();
                    return response()->json(['status' => 'Success', 'message' => 'Update Daily Report success.'], 200);
                } else {
                    DB::rollback();
                    return response()->json(['status' => 'Error', 'message' => 'Update Daily Report failled, with a part error.' ], 202);
                }
            } else {
                DB::rollback();
                return response()->json(['status' => 'Error', 'message' => 'Update Daily Report failled' ], 202);
            }

        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }

    function deleteDailyReport($report_id){
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        DB::beginTransaction();
        try {

            $rq = DailyReport::find($report_id);
            if ($rq){
                $rq->is_active = 0;
                $rq->update();

                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'delete Daily Report success.'], 200);

            } else {
                DB::rollback();
                return response()->json(['status' => 'Info', 'message' => 'delete Daily Report failed.'], 200);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }

    public function printDailyReport($report_id){

        if(!isAccess('read', $this->MenuID)){
            return "You do not have access for this action";
        }

        $report = DailyReport::where('is_active', 1)
                                    ->where('report_id', $report_id)
                                    ->with('plant', 'report_item', 'report_item.part_customer')
                                    ->first();
        if($report){
            $data = ['report'  => $report];
            $pdf = PDF::loadView('production.printDailyReport', $data);
            $pdf->setPaper('A4', 'potrait');
            return $pdf->stream('REQUEST_'.$report_id.'.pdf');
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
