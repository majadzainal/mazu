<?php

namespace App\Http\Controllers\MazuMaster;

use Throwable;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Models\MazuMaster\Unit;
use App\Models\MazuMaster\Product;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\MazuMaster\Warehouse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\MazuMaster\ProductCategory;
use App\Http\Controllers\MazuMaster\StockController;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
    public $MenuID = '00206';

    public $objStock;

    public function __construct()
    {
        $this->objStock = new StockController();
    }

    public function listProduct(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        $store_id = getStoreId();
        $unitList = Unit::where('store_id', $store_id)->where('is_active', 1)->select('unit_id', 'unit_name')->get();
        $productCategoryList = ProductCategory::where('store_id', $store_id)->where('is_active', 1)->get();
        $warehouseList = Warehouse::where('store_id', $store_id)->where('is_active', 1)->select('warehouse_id', 'warehouse_name')->get();

        return view('mazumaster.productTable', [
            'MenuID'        => $this->MenuID,
            'unitList'      => $unitList,
            'productCategoryList'    => $productCategoryList,
            'warehouseList' => $warehouseList
        ]);

    }

    public function loadProduct(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $productList = Product::where('store_id', getStoreId())->where('is_active', 1)
                    ->with('category', 'unit', 'stockWarehouse', 'stockWarehouse.warehouse')
                    ->orderBy('created_at', 'DESC')->get();

        return['data'=> $productList];
    }

    public function addProduct(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        DB::beginTransaction();
        try {

            $file_name = "";
            if($request->images != ""){
                // if($request->images->before)
                $file = $request->file("images");
                $file_name = time().".".$file->getClientOriginalExtension();
                $img = Image::make($file);
                $img->save(public_path('uploads/'.$file_name));
            }


            $product_id = Uuid::uuid4()->toString();
            $product = Product::create([
                'product_id'            => $product_id,
                'product_code'          => $request->product_code,
                'product_name'          => $request->product_name,
                'product_description'   => $request->product_description,
                'price'                 => $request->price,
                'hpp'                   => $request->hpp,
                'stock'                 => $request->stock,
                'unit_id'               => $request->unit_id,
                'product_category_id'   => $request->product_category_id,
                'images'                => $file_name,
                'store_id'              => getStoreId(),
                'created_user'          => Auth::User()->employee->employee_name,
                'is_active'             => 1,
            ]);

            if ($product){
                $this->objStock->plusStock($product->product_id, $request->warehouse_id, $request->stock_warehouse, "Add Product");
                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'add product success.'], 200);
            } else {
                DB::rollback();
                return response()->json(['status' => 'Info', 'message' => 'add product failed.'], 200);
            }

        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }

    }

    public function updateProduct(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }
        // dd($request);
        DB::beginTransaction();
        try {
            $file_name = "";
            if($request->images != ""){
                if($request->images_before != ""){
                    $file_name = $request->images_before;
                    $image_path = public_path('uploads/'.$file_name);  // Value is not URL but directory file path
                    if(File::exists($image_path)) {
                        File::delete($image_path);
                    }
                }
                $file = $request->file("images");
                $file_name = time().".".$file->getClientOriginalExtension();
                $img = Image::make($file);
                $img->save(public_path('uploads/'.$file_name));
            }else{
                $file_name = $request->images_before;
            }

            $product = Product::find($request->product_id);
            $product->update([
                'product_code'          => $request->product_code,
                'product_name'          => $request->product_name,
                'product_description'   => $request->product_description,
                'price'                 => $request->price,
                'hpp'                   => $request->hpp,
                'unit_id'               => $request->unit_id,
                'product_category_id'   => $request->product_category_id,
                'images'                => $file_name,
                'updated_user'          => Auth::User()->employee->employee_name,

            ]);

            if ($product){
                $this->objStock->plusStock($product->product_id, $request->warehouse_id, $request->stock_warehouse, "Edit Product");
                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'edit product success.'], 200);
            } else {
                DB::rollback();
                return response()->json(['status' => 'Info', 'message' => 'edit product failed.'], 200);
            }

        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }

    }

    public function deleteProduct($id)
    {

        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            $product = Product::find($id);
            if ($product){
                $product->is_active = 0;
                $product->update();

                return response()->json(['status' => 'Success', 'message' => 'delete product success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'delete product failed.'], 200);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }
}
