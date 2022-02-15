<?php

namespace App\Http\Controllers\MazuMaster;

use Throwable;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MazuMaster\Warehouse;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\MazuMaster\ExclusiveReseller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ExclusiveResellerController extends Controller
{
    public $MenuID = '00401';

    public function listExcReseller(){
        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        $store_id = getStoreId();
        $warehouseList = Warehouse::where('is_active', 1)->where('store_id', $store_id)->get();

        return view('mazumaster.excResellerTable', [
            'MenuID' => $this->MenuID,
            'warehouseList' => $warehouseList,
        ]);

    }

    public function loadExcReseller(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $excResellerList = ExclusiveReseller::where('store_id', getStoreId())
                    ->with('warehouse')
                    ->where('is_active', 1)->orderBy('created_at', 'DESC')->get();
        return['data'=> $excResellerList];
    }

    public function addExcReseller(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            $exc_reseller_id = Uuid::uuid4()->toString();
            ExclusiveReseller::create([
                'exc_reseller_id'               => $exc_reseller_id,
                'reseller_code'                   => $request->reseller_code,
                'reseller_name'                   => $request->reseller_name,
                'description'                   => $request->description,
                'date_of_birth'                       => $request->date_of_birth,
                'email'                       => $request->email,
                'telephone'                       => $request->telephone,
                'address'                       => $request->address,
                'warehouse_id'                  => $request->warehouse_id,
                'store_id'                      => getStoreId(),
                'is_active'                     => 1,
                'created_user'                  => Auth::User()->employee->employee_name,
            ]);

            return response()->json(['status' => 'Success', 'message' => 'Add exclusive reseller success.'], 200);

        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function updateExcReseller(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            $reseller = ExclusiveReseller::find($request->exc_reseller_id);
            if($reseller){
                $reseller->update([
                    'reseller_code'                   => $request->reseller_code,
                    'reseller_name'                   => $request->reseller_name,
                    'description'                   => $request->description,
                    'date_of_birth'                       => $request->date_of_birth,
                    'email'                       => $request->email,
                    'telephone'                       => $request->telephone,
                    'address'                       => $request->address,
                    'warehouse_id'                  => $request->warehouse_id,
                    'updated_user'                  => Auth::User()->employee->employee_name,
                ]);

                return response()->json(['status' => 'Success', 'message' => 'Edit exclusive reseller success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'exclusive reseller not found.'], 200);
            }
        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function deleteExcReseller($id)
    {
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            $reseller = ExclusiveReseller::find($id);
            if ($reseller){
                $reseller->is_active = 0;
                $reseller->update();

                return response()->json(['status' => 'Success', 'message' => 'Delete exclusive reseller success.'], 200);
            } else {
                return response()->json(['status' => 'info', 'message' => 'Delete exclusive reseller failed.'], 202);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }
    }
}
