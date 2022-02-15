<?php

namespace App\Http\Controllers\MazuMaster;

use Throwable;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Models\MazuMaster\Endorse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EndorseController extends Controller
{
    public $MenuID = '00211';

    public function listEndorse(){
        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }
        $store_id = getStoreId();
        return view('mazumaster.endorseTable', [
            'MenuID' => $this->MenuID,
        ]);

    }

    public function loadEndorse(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $endorseList = Endorse::where('store_id', getStoreId())
                    ->where('is_active', 1)->orderBy('created_at', 'DESC')->get();
        return['data'=> $endorseList];
    }

    public function addEndorse(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            $endorse_id = Uuid::uuid4()->toString();
            Endorse::create([
                'endorse_id'                   => $endorse_id,
                'endorse_code'                 => $request->endorse_code,
                'endorse_name'                 => $request->endorse_name,
                'date_of_birth'                 => $request->date_of_birth,
                'telephone'                   => $request->telephone,
                'email'                   => $request->email_input,
                'description'                   => $request->description,
                'address'                       => $request->address,
                'store_id'                      => getStoreId(),
                'is_active'                     => 1,
                'created_user'                  => Auth::User()->employee->employee_name,
            ]);

            return response()->json(['status' => 'Success', 'message' => 'Add endorse success.'], 200);

        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function updateEndorse(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            $endorse = Endorse::find($request->endorse_id);
            if($endorse){
                $endorse->update([
                    'endorse_code'                 => $request->endorse_code,
                    'endorse_name'                 => $request->endorse_name,
                    'date_of_birth'                 => $request->date_of_birth,
                    'telephone'                   => $request->telephone,
                    'email'                   => $request->email_input,
                    'description'                   => $request->description,
                    'address'                       => $request->address,
                    'is_active'                     => 1,
                    'updated_user'                  => Auth::User()->employee->employee_name,
                ]);

                return response()->json(['status' => 'Success', 'message' => 'Edit Endorse success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'Endorse not found.'], 200);
            }
        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function deleteEndorse($id)
    {
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            $endorse = Endorse::find($id);
            if ($endorse){
                $endorse->is_active = 0;
                $endorse->update();

                return response()->json(['status' => 'Success', 'message' => 'Delete endorse success.'], 200);
            } else {
                return response()->json(['status' => 'info', 'message' => 'Delete endorse failed.'], 202);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }
    }
}
