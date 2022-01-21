<?php

namespace App\Http\Controllers\MazuMaster;

use Throwable;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Models\MazuMaster\Supplier;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SupplierController extends Controller
{
    public $MenuID = '00208';

    public function listSupplier(){
        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }
        $store_id = getStoreId();
        return view('mazumaster.supplierTable', [
            'MenuID' => $this->MenuID,
        ]);

    }

    public function loadSupplier(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $supplierList = Supplier::where('store_id', getStoreId())
                    ->where('is_active', 1)->orderBy('created_at', 'DESC')->get();
        return['data'=> $supplierList];
    }

    public function addSupplier(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            $supplier_id = Uuid::uuid4()->toString();
            Supplier::create([
                'supplier_id'                   => $supplier_id,
                'supplier_name'                 => $request->supplier_name,
                'date_of_birth'                 => $request->date_of_birth,
                'description'                   => $request->description,
                'address'                       => $request->address,
                'store_id'                      => getStoreId(),
                'is_active'                     => 1,
                'created_user'                  => Auth::User()->employee->employee_name,
            ]);

            return response()->json(['status' => 'Success', 'message' => 'Add customer success.'], 200);

        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function updateSupplier(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            $supplier = Supplier::find($request->supplier_id);
            if($supplier){
                $supplier->update([
                    'supplier_name'                 => $request->supplier_name,
                    'date_of_birth'                 => $request->date_of_birth,
                    'description'                   => $request->description,
                    'address'                       => $request->address,
                    'is_active'                     => 1,
                    'updated_user'                  => Auth::User()->employee->employee_name,
                ]);

                return response()->json(['status' => 'Success', 'message' => 'Edit supplier success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'supplier not found.'], 200);
            }
        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function deleteSupplier($id)
    {
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            $supplier = Supplier::find($id);
            if ($supplier){
                $supplier->is_active = 0;
                $supplier->update();

                return response()->json(['status' => 'Success', 'message' => 'Delete supplier success.'], 200);
            } else {
                return response()->json(['status' => 'info', 'message' => 'Delete supplier failed.'], 202);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }
    }
}
