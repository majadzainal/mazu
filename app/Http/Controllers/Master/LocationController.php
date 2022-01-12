<?php

namespace App\Http\Controllers\Master;

use Throwable;
use Illuminate\Http\Request;
use App\Models\Master\Divisi;
use App\Models\Master\Location;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LocationController extends Controller
{

    public $MenuID = '0201';

    public function listLocation(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return redirect()->back();
        }

        $locationList = Location::where('is_active', 1)->get();
        return view('master.locationTable', [
            'MenuID' => $this->MenuID,
            'locationList' => $locationList,

        ]);

    }

    public function loadLocation(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $locationList = Location::where('is_active', 1)->get();
        return['data'=> $locationList];
    }

    public function addLocation(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            Location::create([
                'location_name'    => $request->location_name,
                'address'          => $request->address,
                'phone'            => $request->phone,
                'email'            => $request->email,
                'website'          => $request->website,
                'is_active'        => 1,
            ]);

            return response()->json(['status' => 'Success', 'message' => 'Add location success.'], 200);

        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function updateLocation(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $location = Location::find($request->location_id);
            if($location){
                $location->update([
                    'location_name'    => $request->location_name,
                    'address'          => $request->address,
                    'phone'            => $request->phone,
                    'email'            => $request->email,
                    'website'          => $request->website,
                ]);

                return response()->json(['status' => 'Success', 'message' => 'Edit location success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'Location not found.'], 200);
            }
        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function deleteLocation($id)
    {
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $location = Location::find($id);
            if ($location){
                $location->is_active = 0;
                $location->update();

                return response()->json(['status' => 'Success', 'message' => 'Delete location success.'], 200);
            } else {
                return response()->json(['status' => 'info', 'message' => 'Delete location failed.'], 202);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => 'Delete location failed.'], 200);
        }
    }
}
