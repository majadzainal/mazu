<?php

namespace App\Http\Controllers\Master;

use Throwable;
use Illuminate\Http\Request;
use App\Models\Master\Status;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StatusController extends Controller
{
    public $MenuID = '0207';

    public function listStatusSupplierImport(){
        $statusList = Status::where('is_active', 1)->where('status_type', 'supplier')->get();

        return view('master.import.statusListSupplier', [
            'statusList' => $statusList,
        ]);
    }
    public function listStatusCustomerImport(){
        $statusList = Status::where('is_active', 1)->where('status_type', 'engineering')->get();

        return view('master.import.statusListSupplier', [
            'statusList' => $statusList,
        ]);
    }

    function listStatus(){
        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        return view('master.partStatusTable', [
            'MenuID'        => $this->MenuID,
        ]);
    }

    public function loadStatus($status_type){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $statusList = Status::where('status_type', $status_type)->where('is_active', 1)->get();
        return['data'=> $statusList];
    }

    public function addStatus(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            Status::create([
                'status_type'    => $request->status_type,
                'status'         => $request->status,
                'is_active'      => 1,
            ]);

            return response()->json(['status' => 'Success', 'message' => 'Add status success.'], 200);

        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function updateStatus(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $status = Status::find($request->status_id);
            if($status){
                $status->update([
                    'status'        => $request->status,
                ]);

                return response()->json(['status' => 'Success', 'message' => 'Edit status success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'status not found.'], 200);
            }
        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function deleteStatus($status_id)
    {
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $status = Status::find($status_id);
            if ($status){
                $status->is_active = 0;
                $status->update();

                return response()->json(['status' => 'Success', 'message' => 'Delete status success.'], 200);
            } else {
                return response()->json(['status' => 'info', 'message' => 'Delete status failed.'], 202);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }
    }
}
