<?php

namespace App\Http\Controllers\MazuProcess;

use Exception;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MazuProcess\CashFlow;
use Illuminate\Support\Facades\Auth;
use App\Models\MazuMaster\EventSchedule;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\MazuProcess\GeneralLedgerController;

class CashInController extends Controller
{
    public $MenuID = '016';
    public $objGl;

    public function __construct()
    {
        $this->objGl = new GeneralLedgerController();
    }

    public function cashInTable(){
        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        $isEvent = isEvent();
        $eventList = EventSchedule::where('is_active', 1)->where('is_closed', 0)->get();
        return view('mazuprocess.cashInTable', [
            'MenuID' => $this->MenuID,
            'isEvent' => $isEvent,
            'eventList' => $eventList,
        ]);

    }

    public function loadCashIn($start_date, $end_date){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }
        $cashInList = CashFlow::where('store_id', getStoreId())
                    ->whereBetween('cash_flow_date', [$start_date, $end_date])
                    ->where('cash_flow_type', "IN")
                    ->where('is_active', 1)->orderBy('created_at', 'DESC')->get();
        return['data'=> $cashInList];
    }

    public function addCashIN(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {
            $cash_flow_id = Uuid::uuid4()->toString();
            $cashin = CashFlow::create([
                'cash_flow_id'                  => $cash_flow_id,
                'cash_flow_code'                => $request->cash_flow_code,
                'cash_flow_name'                => $request->cash_flow_name,
                'description'                   => $request->description,
                'cash_flow_date'                => $request->cash_flow_date,
                'dec_cash_flow'                 => $request->dec_cash_flow,
                'store_id'                      => getStoreId(),
                'cash_flow_type'                => "IN",
                'is_active'                     => 1,
                'created_user'                  => Auth::User()->employee->employee_name,
            ]);

            $this->objGl->creditCashFlow($cashin);

            return response()->json(['status' => 'Success', 'message' => 'Add cash in success.'], 200);

        } catch (Exception  $e) {
            return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 202);
        }

    }
    public function updateCashIn(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            $cashin = CashFlow::find($request->cash_flow_id);
            if($cashin){
                $cashin->update([
                    'cash_flow_code'                => $request->cash_flow_code,
                    'cash_flow_name'                => $request->cash_flow_name,
                    'description'                   => $request->description,
                    'cash_flow_date'                => $request->cash_flow_date,
                    'dec_cash_flow'                 => $request->dec_cash_flow,
                    'is_active'                     => 1,
                    'updated_user'                  => Auth::User()->employee->employee_name,
                ]);
                $this->objGl->creditCashFlow($cashin);

                return response()->json(['status' => 'Success', 'message' => 'Edit cash in success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'cash out in found.'], 200);
            }
        } catch (Exception  $e) {
            return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 202);
        }

    }

    public function deleteCashIn($id)
    {
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            $cashin = CashFlow::find($id);
            if ($cashin){
                $cashin->is_active = 0;
                $cashin->created_user = Auth::User()->employee->employee_name;
                $cashin->update();
                $this->objGl->creditCashFlowDelete($cashin);
                return response()->json(['status' => 'Success', 'message' => 'Delete cash in success.'], 200);
            } else {
                return response()->json(['status' => 'info', 'message' => 'Delete cash in failed.'], 202);
            }

        } catch (Exception  $e) {
            return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 202);
        }
    }
}
