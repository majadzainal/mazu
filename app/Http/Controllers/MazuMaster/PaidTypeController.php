<?php

namespace App\Http\Controllers\MazuMaster;

use Throwable;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Models\MazuMaster\PaidType;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PaidTypeController extends Controller
{
    public $MenuID = '00209';

    public function listPaidType(){
        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }
        return view('mazumaster.paidTypeTable', [
            'MenuID' => $this->MenuID,
        ]);

    }

    public function loadPaidType(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $paidTypeList = PaidType::where('store_id', getStoreId())
                    ->where('is_active', 1)->orderBy('created_at', 'DESC')->get();
        return['data'=> $paidTypeList];
    }

    public function addPaidType(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            $paid_type_id = Uuid::uuid4()->toString();
            PaidType::create([
                'paid_type_id'                  => $paid_type_id,
                'type_name'                     => $request->type_name,
                'account_name'                  => $request->account_name,
                'account_number'                => $request->account_number,
                'is_credit'                     => $request->is_credit,
                'store_id'                      => getStoreId(),
                'is_active'                     => 1,
                'created_user'                  => Auth::User()->employee->employee_name,
            ]);

            return response()->json(['status' => 'Success', 'message' => 'Add paid type success.'], 200);

        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function updatePaidType(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            $paidType = PaidType::find($request->paid_type_id);
            if($paidType){
                $paidType->update([
                    'type_name'                     => $request->type_name,
                    'account_name'                  => $request->account_name,
                    'account_number'                => $request->account_number,
                    'is_credit'                     => $request->is_credit,
                    'updated_user'                  => Auth::User()->employee->employee_name,
                ]);

                return response()->json(['status' => 'Success', 'message' => 'Edit paid type success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'paid type not found.'], 200);
            }
        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function deletePaidType($id)
    {
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            $paidType = PaidType::find($id);
            if ($paidType){
                $paidType->is_active = 0;
                $paidType->update();

                return response()->json(['status' => 'Success', 'message' => 'Delete paid type success.'], 200);
            } else {
                return response()->json(['status' => 'info', 'message' => 'Delete paid type failed.'], 202);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }
    }
}
