<?php

namespace App\Http\Controllers\Master;

use Throwable;
use App\Models\Master\Plant;
use App\Models\Master\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class PlantController extends Controller
{

    public $MenuID = '0203';
    function listPlantImport(){
        $plantList = Plant::where('is_active', 1)->get();

        return view('master.import.plantList', [
            'plantList' => $plantList,
        ]);
    }

    public function listPlant(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return redirect()->back();
        }

        $locationList = Location::where('is_active', 1)->get();
        return view('master.plantTable', [
            'MenuID'       => $this->MenuID,
            'locationList' => $locationList,
        ]);

    }

    public function loadPlant(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $plantList = Plant::where('is_active', 1)->with('location')->get();
        return['data'=> $plantList];
    }

    public function addPlant(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            Plant::create([
                'plant_name'    => $request->plant_name,
                'description'   => $request->description,
                'location_id'   => $request->location_id,
                'is_active'     => 1,
            ]);

            return response()->json(['status' => 'Success', 'message' => 'Add plant success.'], 200);

        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function updatePlant(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $plant = Plant::find($request->plant_id);
            if($plant){
                $plant->update([
                    'plant_name'    => $request->plant_name,
                    'description'   => $request->description,
                    'location_id'   => $request->location_id,
                ]);

                return response()->json(['status' => 'Success', 'message' => 'Edit plant success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'plant not found.'], 200);
            }
        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function deletePlant($id)
    {
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $plant = Plant::find($id);
            if ($plant){
                $plant->is_active = 0;
                $plant->update();

                return response()->json(['status' => 'Success', 'message' => 'Delete plant success.'], 200);
            } else {
                return response()->json(['status' => 'info', 'message' => 'Delete plant failed.'], 202);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }
    }
}
