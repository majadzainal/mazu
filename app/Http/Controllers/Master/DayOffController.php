<?php

namespace App\Http\Controllers\Master;

use Throwable;
use Illuminate\Http\Request;
use App\Models\Master\DayOff;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DayOffController extends Controller
{
    public $MenuID = '0214';

    public function listDayOff(){
        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return redirect()->back();
        }

        return view('master.dayOffTable', [
            'MenuID' => $this->MenuID,
        ]);
    }

    public function loadDayOff($year){

        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $list = DayOff::where('year', $year)->where('is_active', 1)->get();
        $dayList = $list->map(function($item){
            $data['day_off_id'] = $item->day_off_id;
            $data['year'] = $item->year;
            $data['day_off'] = $item->day_off;
            $data['name_day_off'] = $item->name;
            $data['description'] = $item->description;
            $data['title'] = $item->name;
            $data['start'] = $item->day_off;
            $data['borderColor'] = "#FC6180";
            $data['backgroundColor'] = "#FC6180";
            $data['textColor'] = "#FFF";
            return $data;
        });

        return['data'=> $dayList];
    }

    public function addDayOff(Request $request){
        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        try {
            $dayOff = DayOff::create([
                'year'          => $request->year,
                'day_off'       => $request->day_off,
                'name'          => $request->name_day_off,
                'description'   => $request->description,
                'is_active'     => 1,
            ]);
            if($dayOff){
                return response()->json(['status' => 'Success', 'message' => 'Add day off success.'], 200);
            }else{
                return response()->json(['status' => 'Error', 'message' => 'Add day off failled.'], 200);
            }

        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function updateDayOff(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        try {
            $dayOff = DayOff::find($request->day_off_id);
            if($dayOff){
                $dayOff->update([
                    'year'          => $request->year,
                    'day_off'       => $request->day_off,
                    'name'          => $request->name_day_off,
                    'description'   => $request->description,
                ]);

                return response()->json(['status' => 'Success', 'message' => 'Edit day off success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'Day off not found.'], 200);
            }
        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function deleteDayOff($id)
    {

        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }

        try {

            $dayOff = DayOff::find($id);
            if ($dayOff){
                $dayOff->is_active = 0;
                $dayOff->update();

                return response()->json(['status' => 'Success', 'message' => 'Delete day off success.'], 200);
            } else {
                return response()->json(['status' => 'info', 'message' => 'Delete day off failed.'], 202);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => 'Delete day off failed.'], 200);
        }
    }
}
