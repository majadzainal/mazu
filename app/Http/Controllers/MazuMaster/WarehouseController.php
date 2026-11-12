<?php

namespace App\Http\Controllers\MazuMaster;

use Throwable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MazuMaster\Warehouse;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class WarehouseController extends Controller
{
    public $MenuID = '00205';

    public function listWarehouse(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return redirect()->back();
        }

        return view('mazumaster.warehouseTable', [
            'MenuID'       => $this->MenuID,
        ]);

    }

    public function loadWarehouse(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $warehouseList = Warehouse::where('store_id', getStoreId())->where('is_active', 1)->get();
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
                'store_id'          => getStoreId(),
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
