<?php

namespace App\Http\Controllers\MazuMaster;

use Throwable;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Models\MazuMaster\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\MazuMaster\CustomerCategory;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CustomerController extends Controller
{
    public $MenuID = '00207';

    public function listCustomer(){
        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }
        $store_id = getStoreId();
        $isEvent = isEvent();
        if($isEvent){
            $custCategoryList = CustomerCategory::where('is_active', 1)->get();
        }else{
            $custCategoryList = CustomerCategory::where('store_id', $store_id)->where('is_active', 1)->get();
        }

        return view('mazumaster.customerTable', [
            'MenuID' => $this->MenuID,
            'custCategoryList' => $custCategoryList,
        ]);

    }

    public function loadCustomer(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $isEvent = isEvent();
        if($isEvent){
            $customerList = Customer::where('is_active', 1)
                    ->with('category')
                    ->orderBy('created_at', 'DESC')->get();
        }else{
            $customerList = Customer::where('store_id', getStoreId())
                    ->with('category')
                    ->where('is_active', 1)->orderBy('created_at', 'DESC')->get();
        }

        return['data'=> $customerList];
    }

    public function addCustomer(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {
            $isEvent = isEvent();
            $store_id = "";
            if($isEvent){
                $store_id = CustomerCategory::where('customer_category_id', $request->customer_category_id)->pluck('store_id')->first();
            }else{
                $store_id = getStoreId();
            }
            $customer_id = Uuid::uuid4()->toString();
            Customer::create([
                'customer_id'                   => $customer_id,
                'customer_name'                 => $request->customer_name,
                'date_of_birth'                 => $request->date_of_birth,
                'description'                   => $request->description,
                'address'                       => $request->address,
                'email'                         => $request->email,
                'customer_category_id'          => $request->customer_category_id,
                'store_id'                      => $store_id,
                'is_active'                     => 1,
                'created_user'                  => Auth::User()->employee->employee_name,
            ]);

            return response()->json(['status' => 'Success', 'message' => 'Add customer success.'], 200);

        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function updateCustomer(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            $customer = Customer::find($request->customer_id);
            if($customer){
                $customer->update([
                    'customer_name'                 => $request->customer_name,
                    'date_of_birth'                 => $request->date_of_birth,
                    'description'                   => $request->description,
                    'address'                       => $request->address,
                    'email'                         => $request->email,
                    'customer_category_id'          => $request->customer_category_id,
                    'is_active'                     => 1,
                    'updated_user'                  => Auth::User()->employee->employee_name,
                ]);

                return response()->json(['status' => 'Success', 'message' => 'Edit customer success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'customer not found.'], 200);
            }
        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function deleteCustomer($id)
    {
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            $customer = Customer::find($id);
            if ($customer){
                $customer->is_active = 0;
                $customer->update();

                return response()->json(['status' => 'Success', 'message' => 'Delete customer success.'], 200);
            } else {
                return response()->json(['status' => 'info', 'message' => 'Delete customer failed.'], 202);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }
    }
}
