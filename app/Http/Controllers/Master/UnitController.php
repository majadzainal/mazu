<?php

namespace App\Http\Controllers\Master;

use Throwable;
use App\Models\Master\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UnitController extends Controller
{
    public $MenuID = '0209';

    public function listUnitImport(){
        $unitList = Unit::where('is_active', 1)->get();

        return view('master.import.unitList', [
            'unitList' => $unitList,
        ]);
    }

    public function listUnit(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return redirect()->back();
        }

        return view('master.unitTable', [
            'MenuID' => $this->MenuID,
        ]);

    }

    public function loadUnit(){

        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $unitList = Unit::where('is_active', 1)->get();

        return['data'=> $unitList];
    }

    public function addUnit(Request $request){

        try {

            if ($request->unit_id == ""){

                if(!isAccess('create', $this->MenuID)){
                    return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
                }
                if(isOpname()){
                    return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
                }


                Unit::create([
                    'unit_name'    => $request->unit_name,
                    'is_active'    => 1,
                ]);

                return response()->json(['status' => 'Success', 'message' => 'Add unit success.'], 200);
            } else {

                if(!isAccess('update', $this->MenuID)){
                    return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
                }
                if(isOpname()){
                    return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
                }

                $unit = Unit::find($request->unit_id);
                if($unit){
                    $unit->update([
                        'unit_name'    => $request->unit_name,
                    ]);

                    return response()->json(['status' => 'Success', 'message' => 'Edit unit success.'], 200);
                } else {
                    return response()->json(['status' => 'Info', 'message' => 'unit not found.'], 200);
                }
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
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

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
