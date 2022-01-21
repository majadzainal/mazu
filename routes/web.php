<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\MazuMaster\ProductCategory;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Master\UserController;
use App\Http\Controllers\Setting\RoleController;
use App\Http\Controllers\MazuMaster\UnitController;
use App\Http\Controllers\Setting\CounterController;
use App\Http\Controllers\MazuMaster\StoreController;
use App\Http\Controllers\MazuMaster\ProductController;
use App\Http\Controllers\MazuMaster\CustomerController;
use App\Http\Controllers\MazuMaster\SupplierController;
use App\Http\Controllers\MazuMaster\WarehouseController;
use App\Http\Controllers\Setting\NumberingFormController;
use App\Http\Controllers\MazuMaster\ProductCategoryController;
use App\Http\Controllers\MazuMaster\CustomerCategoryController;
use App\Http\Controllers\MazuProcess\PurchaseOrderSupplierController;

$this->path = public_path('assets/files/import/');
//use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        return redirect("/dashboard");
    } else {
        return view('login');
    }
});


Auth::routes();

Route::group(['middleware' => 'auth'], function () {

    //=============================DASHBOARD 001 ==========================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('HOME');

    //=============================MASTER STORE 00201==========================
    Route::get('/master/store/table', [StoreController::class, 'listStore']);
    Route::get('/master/store/load', [StoreController::class, 'loadStore']);
    Route::post('/master/store/add', [StoreController::class, 'addStore']);
    Route::post('/master/store/update', [StoreController::class, 'updateStore']);
    Route::get('/master/store/delete/{id}', [StoreController::class, 'deleteStore']);

    //=============================MASTER PRODUCT CATEGORY 00202==========================
    Route::get('/master/product-category/table', [ProductCategoryController::class, 'listCategory']);
    Route::get('/master/product-category/load', [ProductCategoryController::class, 'loadCategory']);
    Route::post('/master/product-category/add', [ProductCategoryController::class, 'addCategory']);
    Route::post('/master/product-category/update', [ProductCategoryController::class, 'updateCategory']);
    Route::get('/master/product-category/delete/{id}', [ProductCategoryController::class, 'deleteCategory']);

    //=============================MASTER CUSTOMER CATGEORY 00203==========================
    Route::get('/master/customer-category/table', [CustomerCategoryController::class, 'listCategory']);
    Route::get('/master/customer-category/load', [CustomerCategoryController::class, 'loadCategory']);
    Route::post('/master/customer-category/add', [CustomerCategoryController::class, 'addCategory']);
    Route::post('/master/customer-category/update', [CustomerCategoryController::class, 'updateCategory']);
    Route::get('/master/customer-category/delete/{id}', [CustomerCategoryController::class, 'deleteCategory']);

    //=============================MASTER UNIT 00204==========================
    Route::get('/master/unit/table', [UnitController::class, 'listUnit']);
    Route::get('/master/unit/load', [UnitController::class, 'loadUnit']);
    Route::post('/master/unit/add', [UnitController::class, 'addUnit']);
    Route::post('/master/unit/update', [UnitController::class, 'updateUnit']);
    Route::get('/master/unit/delete/{id}', [UnitController::class, 'deleteUnit']);

    //=============================WAREHOUSE 00205==========================
    Route::get('/master/warehouse/table', [WarehouseController::class, 'listWarehouse']);
    Route::get('/master/warehouse/load', [WarehouseController::class, 'loadWarehouse']);
    Route::post('/master/warehouse/add', [WarehouseController::class, 'addWarehouse']);
    Route::post('/master/warehouse/update', [WarehouseController::class, 'updateWarehouse']);
    Route::get('/master/warehouse/delete/{id}', [WarehouseController::class, 'deleteWarehouse']);

    //=============================PRODUCT 00206==========================
    Route::get('/master/product/table', [ProductController::class, 'listProduct']);
    Route::get('/master/product/load', [ProductController::class, 'loadProduct']);
    Route::post('/master/product/add', [ProductController::class, 'addProduct']);
    Route::post('/master/product/update', [ProductController::class, 'updateProduct']);
    Route::get('/master/product/delete/{id}', [ProductController::class, 'deleteProduct']);

    //=============================CUSTOMER 00207==========================
    Route::get('/master/customer/table', [CustomerController::class, 'listCustomer']);
    Route::get('/master/customer/load', [CustomerController::class, 'loadCustomer']);
    Route::post('/master/customer/add', [CustomerController::class, 'addCustomer']);
    Route::post('/master/customer/update', [CustomerController::class, 'updateCustomer']);
    Route::get('/master/customer/delete/{id}', [CustomerController::class, 'deleteCustomer']);

    //=============================SUPPLIER 00208==========================
    Route::get('/master/supplier/table', [SupplierController::class, 'listSupplier']);
    Route::get('/master/supplier/load', [SupplierController::class, 'loadSupplier']);
    Route::post('/master/supplier/add', [SupplierController::class, 'addSupplier']);
    Route::post('/master/supplier/update', [SupplierController::class, 'updateSupplier']);
    Route::get('/master/supplier/delete/{id}', [SupplierController::class, 'deleteSupplier']);

    //=============================PO SUPPLIER 003==========================
    Route::get('/master/purchase-order-supplier/table', [PurchaseOrderSupplierController::class, 'listPOSupplier']);
    Route::get('/master/purchase-order-supplier/load', [PurchaseOrderSupplierController::class, 'loadPOSupplier']);
    Route::get('/master/purchase-order-supplier/load-roduct', [PurchaseOrderSupplierController::class, 'loadProduct']);
    // Route::post('/master/purchase-order-supplier/add', [PurchaseOrderSupplier::class, 'addSupplier']);
    // Route::post('/master/purchase-order-supplier/update', [PurchaseOrderSupplier::class, 'updateSupplier']);
    // Route::get('/master/purchase-order-supplier/delete/{id}', [PurchaseOrderSupplier::class, 'deleteSupplier']);


     //=============================USER 9980==========================
     Route::get('/users/table', [UserController::class, 'listUser']);
     Route::get('/users/load', [UserController::class, 'loadUser']);
     Route::post('/users/add', [UserController::class, 'addUser']);
     Route::post('/users/update', [UserController::class, 'updateUser']);
     Route::post('/users/update-profile', [UserController::class, 'updateProfile']);
     Route::post('/users/update-password', [UserController::class, 'updatePassword']);
     Route::get('/users/delete/{id}', [UserController::class, 'deleteUser']);

    //=============================SETTING NUMBERING FORM 99901==========================
    Route::get('/setting/numbering-form-counter/table', [NumberingFormController::class, 'listNumberingForm']);
    Route::post('/setting/numbering-form-counter/update', [NumberingFormController::class, 'updateNumberingForm']);
    Route::get('/setting/numbering-form-counter/get/{id}', [NumberingFormController::class, 'getNumberingForm']);


    //=============================SETTING COUNTER 99901==========================
    Route::get('/setting/counter/load', [CounterController::class, 'loadCounter']);
    Route::post('/setting/counter/add', [CounterController::class, 'addCounter']);
    Route::post('/setting/counter/update', [CounterController::class, 'updateCounter']);
    Route::get('/setting/counter/delete/{id}', [CounterController::class, 'deleteCounter']);

    //=============================ROLE 99902==========================
    Route::get('/master/role/table', [RoleController::class, 'listRole']);
    Route::get('/master/role/load', [RoleController::class, 'loadRole']);
    Route::post('/master/role/add', [RoleController::class, 'addRole']);
    Route::post('/master/role/update', [RoleController::class, 'updateRole']);
    Route::get('/master/role/delete/{id}', [RoleController::class, 'deleteRole']);
});
