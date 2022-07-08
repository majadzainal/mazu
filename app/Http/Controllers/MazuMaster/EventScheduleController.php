<?php

namespace App\Http\Controllers\MazuMaster;

use Exception;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\MazuMaster\EventSchedule;
use RealRashid\SweetAlert\Facades\Alert;

class EventScheduleController extends Controller
{
    public $MenuID = '00212';

    public function listEventSchedule(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        return view('mazumaster.eventSchedule', [
            'MenuID'            => $this->MenuID,
        ]);
    }

    public function loadEventSchedule(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $eventScheduleList = EventSchedule::where('is_active', 1)
                ->orderBy('start_date', 'DESC')
                ->get();
        return['data'=> $eventScheduleList];
    }

    public function addEventSchedule(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }


        try {

            $event_schedule_id = Uuid::uuid4()->toString();

            EventSchedule::create([
                'event_schedule_id'         => $event_schedule_id,
                'event_name'                => $request->event_name,
                'description'               => $request->description,
                'start_date'                => $request->start_date,
                'end_date'                  => $request->end_date,
                'is_closed'                 => 0,
                'user_created'              => Auth::User()->employee->employee_name,
                'is_active'                 => 1,
                'store_id'                  => getStoreId(),
            ]);

            return response()->json(['status' => 'Success', 'message' => 'Add event schedule success.'], 200);

        } catch (Exception  $e) {
            return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 202);
        }

    }

    public function updateEventSchedule(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }

        try {

            $eventSchedule = EventSchedule::find($request->event_schedule_id);
            if($eventSchedule){
                $eventSchedule->update([
                    'event_name'                => $request->event_name,
                    'description'               => $request->description,
                    'start_date'                => $request->start_date,
                    'end_date'                  => $request->end_date,
                ]);

                return response()->json(['status' => 'Success', 'message' => 'Edit event schedule success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'event schedule not found.'], 200);
            }
        } catch (Exception  $e) {
            return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 202);
        }

    }

    public function deleteEventSchedule($id)
    {
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }

        try {

            $eventSchedule = EventSchedule::find($id);
            if ($eventSchedule){
                $eventSchedule->is_active = 0;
                $eventSchedule->update();

                return response()->json(['status' => 'Success', 'message' => 'Delete event schedule success.'], 200);
            } else {
                return response()->json(['status' => 'info', 'message' => 'Delete event schedule failed.'], 202);
            }

        } catch (Exception  $e) {
            return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 202);
        }
    }

    public function closeEventSchedule($id)
    {
        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }

        try {

            $eventSchedule = EventSchedule::find($id);
            if ($eventSchedule){
                $eventSchedule->end_date = Carbon::now();
                $eventSchedule->is_closed = 1;
                $eventSchedule->is_closed = 1;
                $eventSchedule->user_closed = Auth::User()->employee->employee_name;
                $eventSchedule->update();

                return response()->json(['status' => 'Success', 'message' => 'Close event schedule success.'], 200);
            } else {
                return response()->json(['status' => 'info', 'message' => 'Close event schedule failed.'], 202);
            }

        } catch (Exception  $e) {
            return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 202);
        }
    }

    public function eventScheduleIsOpen(){
        $eventScheduleIsOpen = EventSchedule::where('is_closed', 0)
                ->where('is_active', 1)
                ->where('store_id',  getStoreId())
                ->get()->first();

        return $eventScheduleIsOpen;
    }
}
