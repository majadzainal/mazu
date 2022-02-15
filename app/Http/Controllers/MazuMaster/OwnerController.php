<?php

namespace App\Http\Controllers\MazuMaster;

use Throwable;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Models\MazuMaster\Owner;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OwnerController extends Controller
{
    public $MenuID = '00210';

    public function listOwner(){
        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }
        $store_id = getStoreId();
        return view('mazumaster.ownerTable', [
            'MenuID' => $this->MenuID,
        ]);

    }

    public function loadOwner(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $ownerList = Owner::where('store_id', getStoreId())
                    ->where('is_active', 1)->orderBy('created_at', 'DESC')->get();
        return['data'=> $ownerList];
    }

    public function addOwner(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            $owner_id = Uuid::uuid4()->toString();
            Owner::create([
                'owner_id'                   => $owner_id,
                'owner_code'                 => $request->owner_code,
                'owner_name'                 => $request->owner_name,
                'date_of_birth'                 => $request->date_of_birth,
                'telephone'                   => $request->telephone,
                'email'                   => $request->email_input,
                'description'                   => $request->description,
                'address'                       => $request->address,
                'store_id'                      => getStoreId(),
                'is_active'                     => 1,
                'created_user'                  => Auth::User()->employee->employee_name,
            ]);

            return response()->json(['status' => 'Success', 'message' => 'Add owner success.'], 200);

        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function updateOwner(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            $owner = Owner::find($request->owner_id);
            if($owner){
                $owner->update([
                    'owner_code'                    => $request->owner_code,
                    'owner_name'                    => $request->owner_name,
                    'date_of_birth'                 => $request->date_of_birth,
                    'telephone'                     => $request->telephone,
                    'email'                         => $request->email_input,
                    'description'                   => $request->description,
                    'address'                       => $request->address,
                    'is_active'                     => 1,
                    'updated_user'                  => Auth::User()->employee->employee_name,
                ]);

                return response()->json(['status' => 'Success', 'message' => 'Edit owner success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'owner not found.'], 200);
            }
        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function deleteOwner($id)
    {
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            $owner = Owner::find($id);
            if ($owner){
                $owner->is_active = 0;
                $owner->update();

                return response()->json(['status' => 'Success', 'message' => 'Delete owner success.'], 200);
            } else {
                return response()->json(['status' => 'info', 'message' => 'Delete owner failed.'], 202);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }
    }
}
