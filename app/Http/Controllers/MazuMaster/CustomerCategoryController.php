<?php

namespace App\Http\Controllers\MazuMaster;

use Throwable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\MazuMaster\CustomerCategory;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CustomerCategoryController extends Controller
{
    public $MenuID = '00203';

    public function listCategory(){
        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        return view('mazumaster.custCategoryTable', [
            'MenuID' => $this->MenuID,
        ]);

    }

    public function loadCategory(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $custCategoryList = CustomerCategory::where('store_id', getStoreId())
                    ->where('is_active', 1)->orderBy('created_at', 'DESC')->get();
        return['data'=> $custCategoryList];
    }

    public function addCategory(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            CustomerCategory::create([
                'cust_category_code'             => $request->cust_category_code,
                'cust_category_name'             => $request->cust_category_name,
                'cust_category_description'      => $request->cust_category_description,
                'discount_percent'              => $request->discount_percent,
                'store_id'                  => getStoreId(),
                'is_active'                 => 1,
            ]);

            return response()->json(['status' => 'Success', 'message' => 'Add customer category success.'], 200);

        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function updateCategory(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            $category = CustomerCategory::find($request->customer_category_id);
            if($category){
                $category->update([
                    'cust_category_name'             => $request->cust_category_name,
                    'cust_category_description'      => $request->cust_category_description,
                    'discount_percent'              => $request->discount_percent,
                ]);

                return response()->json(['status' => 'Success', 'message' => 'Edit customer category success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'customer customer not found.'], 200);
            }
        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function deleteCategory($id)
    {
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            $category = CustomerCategory::find($id);
            if ($category){
                $category->is_active = 0;
                $category->update();

                return response()->json(['status' => 'Success', 'message' => 'Delete customer category success.'], 200);
            } else {
                return response()->json(['status' => 'info', 'message' => 'Delete customer category failed.'], 202);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }
    }
}
