<?php

namespace App\Http\Controllers\MazuMaster;

use Throwable;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Models\MazuMaster\Outlet;
use App\Http\Controllers\Controller;
use App\Models\MazuMaster\Warehouse;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OutletController extends Controller
{
    public $MenuID = '00301';

    public function listOutlet(){
        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        $store_id = getStoreId();
        $warehouseList = Warehouse::where('is_active', 1)->where('store_id', $store_id)->get();

        return view('mazumaster.outletTable', [
            'MenuID' => $this->MenuID,
            'warehouseList' => $warehouseList,
        ]);

    }

    public function loadOutlet(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $outletLis = Outlet::where('store_id', getStoreId())
                    ->with('warehouse')
                    ->where('is_active', 1)->orderBy('created_at', 'DESC')->get();
        return['data'=> $outletLis];
    }

    public function addOutlet(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            $outlet_id = Uuid::uuid4()->toString();
            Outlet::create([
                'outlet_id'                     => $outlet_id,
                'outlet_code'                   => $request->outlet_code,
                'outlet_name'                   => $request->outlet_name,
                'description'                   => $request->description,
                'address'                       => $request->address,
                'warehouse_id'                  => $request->warehouse_id,
                'store_id'                      => getStoreId(),
                'is_active'                     => 1,
                'created_user'                  => Auth::User()->employee->employee_name,
            ]);

            return response()->json(['status' => 'Success', 'message' => 'Add outlet success.'], 200);

        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function updateOutlet(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            $outlet = Outlet::find($request->outlet_id);
            if($outlet){
                $outlet->update([
                    'outlet_code'                   => $request->outlet_code,
                    'outlet_name'                   => $request->outlet_name,
                    'description'                   => $request->description,
                    'address'                       => $request->address,
                    'warehouse_id'                  => $request->warehouse_id,
                    'updated_user'                  => Auth::User()->employee->employee_name,
                ]);

                return response()->json(['status' => 'Success', 'message' => 'Edit outlet success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'outlet not found.'], 200);
            }
        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function deleteOutlet($id)
    {
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            $outlet = Outlet::find($id);
            if ($outlet){
                $outlet->is_active = 0;
                $outlet->update();

                return response()->json(['status' => 'Success', 'message' => 'Delete outlet success.'], 200);
            } else {
                return response()->json(['status' => 'info', 'message' => 'Delete outlet failed.'], 202);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }
    }
}
