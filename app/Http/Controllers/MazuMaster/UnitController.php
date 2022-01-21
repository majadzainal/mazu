<?php

namespace App\Http\Controllers\MazuMaster;

use Throwable;
use App\Models\MazuMaster\Unit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UnitController extends Controller
{
    public $MenuID = '00204';

    public function listUnit(){
        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        return view('mazumaster.unitTable', [
            'MenuID' => $this->MenuID,
        ]);

    }

    public function loadUnit(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $unitList = Unit::where('store_id', getStoreId())
                    ->where('is_active', 1)->orderBy('created_at', 'DESC')->get();
        return['data'=> $unitList];
    }

    public function addUnit(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            Unit::create([
                'unit_name'             => $request->unit_name,
                'store_id'              => getStoreId(),
                'is_active'             => 1,
            ]);

            return response()->json(['status' => 'Success', 'message' => 'Add unit success.'], 200);

        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function updateUnit(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            $unit = Unit::find($request->unit_id);
            if($unit){
                $unit->update([
                    'unit_name'             => $request->unit_name,
                ]);

                return response()->json(['status' => 'Success', 'message' => 'Edit unit success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'unit not found.'], 200);
            }
        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function deleteUnit($id)
    {
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            $unit = Unit::find($id);
            if ($unit){
                $unit->is_active = 0;
                $unit->update();

                return response()->json(['status' => 'Success', 'message' => 'Delete unit success.'], 200);
            } else {
                return response()->json(['status' => 'info', 'message' => 'Delete unit failed.'], 202);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }
    }
}
