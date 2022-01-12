<?php

namespace App\Http\Controllers\Setting;

use Throwable;
use Illuminate\Http\Request;
use App\Models\Setting\Counter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CounterController extends Controller
{
    public $MenuID = '99901';
    public function loadCounter(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $counterList = Counter::where('is_active', 1)->get();
        return['data'=> $counterList];
    }

    public function addCounter(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        $validator = Validator::make($request->all(), [
            // 'username' => 'required|unique:tm_user,username,'.$request->id.',user_id',
            'counter_name'  => 'required|unique:ts_counter,counter_name',
            'counter'       => 'required|integer',
            'length'        => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'Error', 'message' => 'Please input unique counter name, counter with integer and length with integer.'], 202);
        }

        try {

            Counter::create([
                'counter_name'    => $request->counter_name,
                'counter'         => $request->counter,
                'length'          => $request->length,
                'created_user'    => Auth::User()->employee->employee_name,
                'is_active'       => 1,
            ]);

            return response()->json(['status' => 'Success', 'message' => 'Add counter success.'], 200);

        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function updateCounter(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        $validator = Validator::make($request->all(), [
            'counter_name'  => 'required|unique:ts_counter,counter_name,'.$request->counter_id.',counter_id',
            'counter'       => 'required|integer',
            'length'        => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'Error', 'message' => 'Please input unique counter name, counter with integer and length with integer.'], 202);
        }

        try {

            $counter = Counter::find($request->counter_id);
            if($counter){
                $counter->update([
                    'counter_name'      => $request->counter_name,
                    'counter'           => $request->counter,
                    'length'            => $request->length,
                    'updated_user'      => Auth::User()->employee->employee_name,
                ]);

                return response()->json(['status' => 'Success', 'message' => 'Edit counter success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'Counter not found.'], 200);
            }
        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function deleteCounter($counter_id)
    {
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $counter = Counter::find($counter_id);
            if ($counter){
                $counter->is_active = 0;
                $counter->updated_user = Auth::User()->employee->employee_name;
                $counter->update();

                return response()->json(['status' => 'Success', 'message' => 'Delete counter success.'], 200);
            } else {
                return response()->json(['status' => 'info', 'message' => 'Delete counter failed.'], 202);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }
    }
}
