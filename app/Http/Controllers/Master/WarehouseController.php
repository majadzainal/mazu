<?php

namespace App\Http\Controllers\Master;

use Throwable;
use App\Models\Master\Warehouse;
use App\Models\Master\Plant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class WarehouseController extends Controller
{

    public $MenuID = '0212';

    function listWarehouseImport(){
        $warehouseList = Warehouse::with('plant')->where('is_active', 1)->get();

        return view('master.import.warehouseList', [
            'warehouseList' => $warehouseList,
        ]);
    }

    public function listWarehouse(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return redirect()->back();
        }

        $plantList = Plant::where('is_active', 1)->get();
        return view('master.warehouseTable', [
            'MenuID'       => $this->MenuID,
            'plantList' => $plantList,
        ]);

    }

    public function loadWarehouse(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $warehouseList = Warehouse::where('is_active', 1)->with('plant')->get();
        return['data'=> $warehouseList];
    }

    public function addWarehouse(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            Warehouse::create([
                'warehouse_name'    => $request->warehouse_name,
                'description'       => $request->description,
                'plant_id'          => $request->plant_id,
                'is_active'         => 1,
            ]);

            return response()->json(['status' => 'Success', 'message' => 'Add warehouse success.'], 200);

        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function updateWarehouse(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $warehouse = Warehouse::find($request->warehouse_id);
            if($warehouse){
                $warehouse->update([
                    'warehouse_name'    => $request->warehouse_name,
                    'description'       => $request->description,
                    'plant_id'          => $request->plant_id,
                ]);

                return response()->json(['status' => 'Success', 'message' => 'Edit warehouse success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'warehouse not found.'], 200);
            }
        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function deleteWarehouse($id)
    {
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $warehouse = Warehouse::find($id);
            if ($warehouse){
                $warehouse->is_active = 0;
                $warehouse->update();

                return response()->json(['status' => 'Success', 'message' => 'Delete warehouse success.'], 200);
            } else {
                return response()->json(['status' => 'info', 'message' => 'Delete warehouse failed.'], 202);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }
    }
}
