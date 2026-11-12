<?php

namespace App\Http\Controllers\MazuProcess;

use Exception;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MazuProcess\CashOut;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\MazuMaster\EventSchedule;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\MazuProcess\GeneralLedgerController;

class CashOutController extends Controller
{
    public $MenuID = '014';
    public $objGl;

    public function __construct()
    {
        $this->objGl = new GeneralLedgerController();
    }

    public function cashOutTable(){
        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        $isEvent = isEvent();
        $eventList = EventSchedule::where('is_active', 1)->where('is_closed', 0)->get();

        return view('mazuprocess.cashOutTable', [
            'MenuID' => $this->MenuID,
            'isEvent' => $isEvent,
            'eventList' => $eventList,
        ]);

    }

    public function loadCashOut($start_date, $end_date){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }
        $cashOutList = CashOut::where('store_id', getStoreId())
                    ->whereBetween('cash_out_date', [$start_date, $end_date])
                    ->where('is_active', 1)->orderBy('created_at', 'DESC')->get();
        return['data'=> $cashOutList];
    }

    public function addCashOut(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {
            $cash_out_id = Uuid::uuid4()->toString();
            $cashOut = CashOut::create([
                'cash_out_id'                   => $cash_out_id,
                'cash_out_code'                 => $request->cash_out_code,
                'cash_out_name'                 => $request->cash_out_name,
                'description'                   => $request->description,
                'cash_out_date'                 => $request->cash_out_date,
                'dec_cash_out'                  => $request->dec_cash_out,
                'store_id'                      => getStoreId(),
                'is_active'                     => 1,
                'created_user'                  => Auth::User()->employee->employee_name,
            ]);

            $this->objGl->debitCashOut($cashOut);

            return response()->json(['status' => 'Success', 'message' => 'Add cash out success.'], 200);

        } catch (Exception  $e) {
            return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 202);
        }

    }

    public function updateCashOut(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $cashOut = CashOut::find($request->cash_out_id);
            if($cashOut){
                $cashOut->update([
                    'cash_out_code'                 => $request->cash_out_code,
                    'cash_out_name'                 => $request->cash_out_name,
                    'description'                   => $request->description,
                    'cash_out_date'                 => $request->cash_out_date,
                    'dec_cash_out'                  => $request->dec_cash_out,
                    'updated_user'                  => Auth::User()->employee->employee_name,
                ]);
                $this->objGl->debitCashOut($cashOut);

                return response()->json(['status' => 'Success', 'message' => 'Edit cash out success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'cash out not found.'], 200);
            }
        } catch (Exception  $e) {
            return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 202);
        }

    }

    public function deleteCashOut($id)
    {
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $cashOut = CashOut::find($id);
            if ($cashOut){
                $cashOut->is_active = 0;
                $cashOut->created_user = Auth::User()->employee->employee_name;
                $cashOut->update();
                $this->objGl->debitCashOutDelete($cashOut);
                return response()->json(['status' => 'Success', 'message' => 'Delete cash out success.'], 200);
            } else {
                return response()->json(['status' => 'info', 'message' => 'Delete cash out failed.'], 202);
            }

        } catch (Exception  $e) {
            return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 202);
        }
    }
}
