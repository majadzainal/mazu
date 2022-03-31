<?php

namespace App\Http\Controllers\MazuMaster;

use Exception;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\MazuMaster\StockOpnameSchedule;

class StockOpnameScheduleController extends Controller
{
    public $MenuID = '01501';

    public function listOpnameSchedule(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        return view('mazumaster.opnameSchedule', [
            'MenuID'            => $this->MenuID,
        ]);
    }

    public function loadOpnameSchedule(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $opnameScheduleList = StockOpnameSchedule::where('is_active', 1)
                ->orderBy('start_datetime', 'DESC')
                ->get();
        return['data'=> $opnameScheduleList];
    }

    public function addOpnameSchedule(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }


        try {
            $opnameScheduleIsOpen = $this->opnameScheduleIsOpen();

            if($opnameScheduleIsOpen){
                return response()->json(['status' => 'Warning', 'message' => 'Add stock opname schedule failed, have exist stock opname schedule or schedule is still opened.'], 200);
            }

            $stock_opname_schedule_id = Uuid::uuid4()->toString();

            StockOpnameSchedule::create([
                'stock_opname_schedule_id'  => $stock_opname_schedule_id,
                'opname_date'               => $request->start_datetime,
                'start_datetime'            => $request->start_datetime,
                'is_closed'                 => 0,
                'user_created'              => Auth::User()->employee->employee_name,
                'is_active'                 => 1,
                'store_id'                  => getStoreId(),
            ]);

            return response()->json(['status' => 'Success', 'message' => 'Add stock opname schedule success.'], 200);

        } catch (Exception  $e) {
            return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 202);
        }

    }

    public function updateOpnameSchedule(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }

        try {

            $opnameSchedule = StockOpnameSchedule::find($request->stock_opname_schedule_id);
            if($opnameSchedule){
                $opnameSchedule->update([
                    'opname_date'               => $request->start_datetime,
                    'start_datetime'            => $request->start_datetime,
                ]);

                return response()->json(['status' => 'Success', 'message' => 'Edit stock opname schedule success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'stock opname schedule not found.'], 200);
            }
        } catch (Exception  $e) {
            return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 202);
        }

    }

    public function deleteOpnameSchedule($id)
    {
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }

        try {

            $opnameSchedule = StockOpnameSchedule::find($id);
            if ($opnameSchedule){
                $opnameSchedule->is_active = 0;
                $opnameSchedule->update();

                return response()->json(['status' => 'Success', 'message' => 'Delete stock opname schedule success.'], 200);
            } else {
                return response()->json(['status' => 'info', 'message' => 'Delete stock opname schedule failed.'], 202);
            }

        } catch (Exception  $e) {
            return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 202);
        }
    }

    public function closeOpnameSchedule($id)
    {
        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }

        try {

            $opnameSchedule = StockOpnameSchedule::find($id);
            if ($opnameSchedule){
                $opnameSchedule->end_datetime = Carbon::now();
                $opnameSchedule->is_closed = 1;
                $opnameSchedule->update();

                return response()->json(['status' => 'Success', 'message' => 'Close stock opname schedule success.'], 200);
            } else {
                return response()->json(['status' => 'info', 'message' => 'Close stock opname schedule failed.'], 202);
            }

        } catch (Exception  $e) {
            return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 202);
        }
    }

    public function opnameScheduleIsOpen(){
        $opnameScheduleIsOpen = StockOpnameSchedule::where('is_closed', 0)
                ->where('is_active', 1)
                ->where('store_id',  getStoreId())
                ->get()->first();

        return $opnameScheduleIsOpen;
    }
}
