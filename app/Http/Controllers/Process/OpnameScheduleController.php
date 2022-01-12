<?php

namespace App\Http\Controllers\Process;

use Throwable;
use Ramsey\Uuid\Uuid;
use App\Models\Master\Plant;
use Illuminate\Http\Request;
use App\Models\Master\OpnameType;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Process\OpnameSchedule;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OpnameScheduleController extends Controller
{
    public $MenuID = '1001';

    public function listOpnameSchedule(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        $plantList = Plant::where('is_active', 1)->get();

        return view('process.opnameSchedule', [
            'MenuID'            => $this->MenuID,
            'plantList'          => $plantList,
        ]);
    }

    public function loadOpnameSchedule(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $opnameScheduleList = OpnameSchedule::where('is_active', 1)
                ->with('plant')
                ->orderBy('start_datetime', 'DESC')
                ->get();
        return['data'=> $opnameScheduleList];
    }

    public function addOpnameSchedule(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }


        try {
            $opnameScheduleIsOpen = $this->opnameScheduleIsOpen($request->plant_id);

            if($opnameScheduleIsOpen){
                return response()->json(['status' => 'Warning', 'message' => 'Add stock opname schedule failed, schedule in plant '.$opnameScheduleIsOpen->plant->plant_name.' is exist and still opened.'], 200);
            }

            $opname_schedule_id = Uuid::uuid4()->toString();

            OpnameSchedule::create([
                'opname_schedule_id'        => $opname_schedule_id,
                'opname_date'               => $request->start_datetime,
                'plant_id'                  => $request->plant_id,
                'start_datetime'            => $request->start_datetime,
                'is_closed'                 => 0,
                'user_created'              => Auth::User()->employee->employee_name,
                'is_active'                 => 1,
            ]);

            return response()->json(['status' => 'Success', 'message' => 'Add stock opname schedule success.'], 200);

        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function updateOpnameSchedule(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }

        try {

            $opnameSchedule = OpnameSchedule::find($request->opname_schedule_id);
            if($opnameSchedule){
                $opnameSchedule->update([
                    'opname_date'               => $request->start_datetime,
                    'plant_id'                  => $request->plant_id,
                    'start_datetime'            => $request->start_datetime,
                ]);

                return response()->json(['status' => 'Success', 'message' => 'Edit stock opname schedule success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'stock opname schedule not found.'], 200);
            }
        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function deleteOpnameSchedule($id)
    {
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }

        try {

            $opnameSchedule = OpnameSchedule::find($id);
            if ($opnameSchedule){
                $opnameSchedule->is_active = 0;
                $opnameSchedule->update();

                return response()->json(['status' => 'Success', 'message' => 'Delete stock opname schedule success.'], 200);
            } else {
                return response()->json(['status' => 'info', 'message' => 'Delete stock opname schedule failed.'], 202);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }
    }

    public function closeOpnameSchedule($id)
    {
        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }

        try {

            $opnameSchedule = OpnameSchedule::find($id);
            if ($opnameSchedule){
                $opnameSchedule->is_closed = 1;
                $opnameSchedule->update();

                return response()->json(['status' => 'Success', 'message' => 'Close stock opname schedule success.'], 200);
            } else {
                return response()->json(['status' => 'info', 'message' => 'Close stock opname schedule failed.'], 202);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }
    }

    public function opnameScheduleIsOpen($plant_id){
        $opnameScheduleIsOpen = OpnameSchedule::where('is_closed', 0)
                ->where('is_active', 1)
                ->where('plant_id',  $plant_id)
                ->with('plant')
                ->get()->first();

        return $opnameScheduleIsOpen;
    }
}
