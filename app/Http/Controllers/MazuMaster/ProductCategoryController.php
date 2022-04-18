<?php

namespace App\Http\Controllers\MazuMaster;

use Throwable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\MazuMaster\ProductCategory;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductCategoryController extends Controller
{
    public $MenuID = '00202';

    public function listCategory(){
        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        return view('mazumaster.productCategoryTable', [
            'MenuID' => $this->MenuID,
        ]);

    }

    public function loadCategory(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $productCategoryList = ProductCategory::where('store_id', getStoreId())
                    ->where('is_active', 1)->orderBy('created_at', 'DESC')->get();
        return['data'=> $productCategoryList];
    }

    public function addCategory(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            ProductCategory::create([
                'category_code'             => $request->category_code,
                'category_name'             => $request->category_name,
                'category_description'      => $request->category_description,
                'store_id'                  => getStoreId(),
                'is_active'                 => 1,
            ]);

            return response()->json(['status' => 'Success', 'message' => 'Add product category success.'], 200);

        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function updateCategory(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $category = ProductCategory::find($request->product_category_id);
            if($category){
                $category->update([
                    'category_name'             => $request->category_name,
                    'category_description'      => $request->category_description,
                ]);

                return response()->json(['status' => 'Success', 'message' => 'Edit product category success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'product category not found.'], 200);
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
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $category = ProductCategory::find($id);
            if ($category){
                $category->is_active = 0;
                $category->update();

                return response()->json(['status' => 'Success', 'message' => 'Delete product category success.'], 200);
            } else {
                return response()->json(['status' => 'info', 'message' => 'Delete product category failed.'], 202);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }
    }
}
