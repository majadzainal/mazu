<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Models\MazuMaster\ProductCategory;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Master\UserController;
use App\Http\Controllers\Setting\RoleController;
use App\Http\Controllers\MazuMaster\UnitController;
use App\Http\Controllers\Setting\CounterController;
use App\Http\Controllers\MazuMaster\OwnerController;
use App\Http\Controllers\MazuMaster\StoreController;
use App\Http\Controllers\MazuMaster\OutletController;
use App\Http\Controllers\MazuMaster\CompanyController;
use App\Http\Controllers\MazuMaster\EndorseController;
use App\Http\Controllers\MazuMaster\ProductController;
use App\Http\Controllers\MazuProcess\CashInController;
use App\Http\Controllers\MazuMaster\CustomerController;
use App\Http\Controllers\MazuMaster\PaidTypeController;
use App\Http\Controllers\MazuMaster\SupplierController;
use App\Http\Controllers\MazuProcess\CashOutController;
use App\Http\Controllers\Process\StockOpnameController;
use App\Http\Controllers\Master\UserSuperuserController;
use App\Http\Controllers\MazuMaster\WarehouseController;
use App\Http\Controllers\Setting\NumberingFormController;
use App\Http\Controllers\Setting\RoleSuperuserController;
use App\Http\Controllers\MazuProcess\ProductionController;
use App\Http\Controllers\MazuProcess\SalesOrderController;
use App\Http\Controllers\MazuMaster\LabelProductController;
use App\Http\Controllers\MazuMaster\EventScheduleController;
use App\Http\Controllers\MazuProcess\SalesOrderPoController;
use App\Http\Controllers\MazuMaster\BroadcastEmailController;
use App\Http\Controllers\MazuMaster\InventoryOutletController;
use App\Http\Controllers\MazuMaster\ProductCategoryController;
use App\Http\Controllers\MazuMaster\ProductSupplierController;
use App\Http\Controllers\MazuMaster\CustomerCategoryController;
use App\Http\Controllers\MazuMaster\InventoryProductController;
use App\Http\Controllers\MazuProcess\SalesOrderOwnerController;
use App\Http\Controllers\MazuMaster\ExclusiveResellerController;
use App\Http\Controllers\MazuProcess\ReceivingProductController;
use App\Http\Controllers\MazuProcess\ReportSalesOrderController;
use App\Http\Controllers\MazuProcess\SalesOrderMedsosController;
use App\Http\Controllers\MazuProcess\SalesOrderOutletController;
use App\Http\Controllers\MazuProcess\SalesOrderEndorseController;
use App\Http\Controllers\MazuMaster\StockOpnameScheduleController;
use App\Http\Controllers\MazuProcess\StockOpnameProductController;
use App\Http\Controllers\MazuProcess\DeliveryOrderOutletController;
use App\Http\Controllers\MazuProcess\ReportGeneralLedgerController;
use App\Http\Controllers\MazuProcess\PurchaseOrderCustomerController;
use App\Http\Controllers\MazuProcess\PurchaseOrderMaterialController;
use App\Http\Controllers\MazuProcess\PurchaseOrderSupplierController;
use App\Http\Controllers\MazuProcess\ReportSalesOrderOwnerController;
use App\Http\Controllers\MazuProcess\SalesOrderExcResellerController;
use App\Http\Controllers\MazuProcess\SalesOrderSpecialGiftController;
use App\Http\Controllers\MazuProcess\ReportSalesOrderMedsosController;
use App\Http\Controllers\MazuProcess\ReportSalesOrderOutletController;
use App\Http\Controllers\MazuMaster\InventoryProductSupplierController;
use App\Http\Controllers\MazuProcess\ReportSalesOrderEndorseController;
use App\Http\Controllers\MazuProcess\DeliveryOrderExcResellerController;
use App\Http\Controllers\MazuProcess\ReceivingProductSupplierController;
use App\Http\Controllers\MazuMaster\InventoryExclusiveResellerController;
use App\Http\Controllers\MazuProcess\ReportSalesOrderExcResellerController;
use App\Http\Controllers\MazuProcess\ReportSalesOrderSpesialGiftController;

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

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return "Clear cache success";
});

Route::get('/clear-view', function() {
    $exitCode = Artisan::call('view:clear');
    return "Clear view success";
});

Route::get('/clear-route', function() {
    $exitCode = Artisan::call('route:clear');
    return "Clear route success";
});

Route::get('/clear-config', function() {
    $exitCode = Artisan::call('config:clear');
    return "Clear config success";
});

Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize');
    return "Optimize success";
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {

    //=============================DASHBOARD 001 ==========================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('HOME');
    Route::get('/dashboard/sales-order/load/{start_date}/{end_date}', [DashboardController::class, 'loadSales']);
    Route::get('/dashboard/sales-order-product/load/{start_date}/{end_date}', [DashboardController::class, 'loadProductSales']);
    Route::get('/dashboard/sales-order-product-category/load/{start_date}/{end_date}/{product_category_id}', [DashboardController::class, 'loadProductSalesByCategory']);
    Route::get('/dashboard/sales-order-by-product/load/{start_date}/{end_date}/{product_id}', [DashboardController::class, 'loadProductSalesByProduct']);
    Route::get('/dashboard/get-email-birthday', [DashboardController::class, 'getBirthdayMail']);
    Route::post('/dashboard/send-email-birthday', [DashboardController::class, 'addBroadcast']);

    //=============================MASTER STORE 00201==========================
    Route::get('/master/company/table', [CompanyController::class, 'company']);
    Route::get('/master/company/load', [CompanyController::class, 'loadCompany']);
    Route::post('/master/company/update', [CompanyController::class, 'updateCompany']);

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

    //=============================PAID TYPE 00209==========================
    Route::get('/master/paid-type/table', [PaidTypeController::class, 'listPaidType']);
    Route::get('/master/paid-type/load', [PaidTypeController::class, 'loadPaidType']);
    Route::post('/master/paid-type/add', [PaidTypeController::class, 'addPaidType']);
    Route::post('/master/paid-type/update', [PaidTypeController::class, 'updatePaidType']);
    Route::get('/master/paid-type/delete/{id}', [PaidTypeController::class, 'deletePaidType']);

    //=============================OWNER 00210==========================
    Route::get('/master/owner/table', [OwnerController::class, 'listOwner']);
    Route::get('/master/owner/load', [OwnerController::class, 'loadOwner']);
    Route::post('/master/owner/add', [OwnerController::class, 'addOwner']);
    Route::post('/master/owner/update', [OwnerController::class, 'updateOwner']);
    Route::get('/master/owner/delete/{id}', [OwnerController::class, 'deleteOwner']);

    //=============================ENDORSE 00211==========================
    Route::get('/master/endorse/table', [EndorseController::class, 'listEndorse']);
    Route::get('/master/endorse/load', [EndorseController::class, 'loadEndorse']);
    Route::post('/master/endorse/add', [EndorseController::class, 'addEndorse']);
    Route::post('/master/endorse/update', [EndorseController::class, 'updateEndorse']);
    Route::get('/master/endorse/delete/{id}', [EndorseController::class, 'deleteEndorse']);

    //=============================EVENT SCHEDULE 00212==========================
    Route::get('/master/event-schedule/table', [EventScheduleController::class, 'listEventSchedule']);
    Route::get('/master/event-schedule/load', [EventScheduleController::class, 'loadEventSchedule']);
    Route::post('/master/event-schedule/add', [EventScheduleController::class, 'addEventSchedule']);
    Route::post('/master/event-schedule/update', [EventScheduleController::class, 'updateEventSchedule']);
    Route::get('/master/event-schedule/close/{id}', [EventScheduleController::class, 'closeEventSchedule']);
    Route::get('/master/event-schedule/delete/{id}', [EventScheduleController::class, 'deleteEventSchedule']);

    //=============================OUTLET LIST 00301==========================
    Route::get('/master/outlet/table', [OutletController::class, 'listOutlet']);
    Route::get('/master/outlet/load', [OutletController::class, 'loadOutlet']);
    Route::post('/master/outlet/add', [OutletController::class, 'addOutlet']);
    Route::post('/master/outlet/update', [OutletController::class, 'updateOutlet']);
    Route::get('/master/outlet/delete/{id}', [OutletController::class, 'deleteOutlet']);

    //=============================INVENTORY OUTLET LIST 00302==========================
    Route::get('/master/outlet-inventory/table', [InventoryOutletController::class, 'listInventory']);
    Route::get('/master/outlet-inventory/load-outlet', [InventoryOutletController::class, 'loadOutlet']);
    Route::get('/master/outlet-inventory/load/{outlet_id}', [InventoryOutletController::class, 'loadInventory']);
    Route::get('/master/outlet-inventory/load-log-stock/{outlet_id}/{product_id}', [InventoryOutletController::class, 'loadLogStock']);
    Route::get('/master/outlet-inventory/load-log-stock-supplier/{outlet_id}/{product_supplier_id}', [InventoryOutletController::class, 'loadLogStockSupplier']);

    //=============================DO OUTLET 00303==========================
    Route::get('/process/delivery-order-outlet/table', [DeliveryOrderOutletController::class, 'listDOOutlet']);
    Route::get('/process/delivery-order-outlet/load/{start_date}/{end_date}', [DeliveryOrderOutletController::class, 'loadDOOutlet']);
    Route::get('/process/delivery-order-outlet/load-product', [DeliveryOrderOutletController::class, 'loadProduct']);
    Route::post('/process/delivery-order-outlet/add', [DeliveryOrderOutletController::class, 'addDOOutlet']);
    Route::post('/process/delivery-order-outlet/update', [DeliveryOrderOutletController::class, 'updateDOOutlet']);
    Route::get('/process/delivery-order-outlet/delete/{id}', [DeliveryOrderOutletController::class, 'deleteDOOutlet']);
    Route::get('/process/delivery-order-outlet/get-label/{label_product}', [DeliveryOrderOutletController::class, 'getProductLabel']);

    //=============================SALES ORDER OUTLET 00304==========================
    Route::get('/process/sales-order-outlet/table', [SalesOrderOutletController::class, 'listSO']);
    Route::get('/process/sales-order-outlet/load/{start_date}/{end_date}', [SalesOrderOutletController::class, 'loadSO']);
    Route::get('/process/sales-order-outlet/load-product', [SalesOrderOutletController::class, 'loadProduct']);
    Route::get('/process/sales-order-outlet/get-label/{product_label}', [SalesOrderOutletController::class, 'getProductLabel']);
    Route::post('/process/sales-order-outlet/add', [SalesOrderOutletController::class, 'addSO']);
    Route::post('/process/sales-order-outlet/update', [SalesOrderOutletController::class, 'updateSO']);
    Route::get('/process/sales-order-outlet/delete/{so_id}', [SalesOrderOutletController::class, 'deleteSO']);

    //=============================EXCLUSIVE RESELLER LIST 00401==========================
    Route::get('/master/exclusive-reseller/table', [ExclusiveResellerController::class, 'listExcReseller']);
    Route::get('/master/exclusive-reseller/load', [ExclusiveResellerController::class, 'loadExcReseller']);
    Route::post('/master/exclusive-reseller/add', [ExclusiveResellerController::class, 'addExcReseller']);
    Route::post('/master/exclusive-reseller/update', [ExclusiveResellerController::class, 'updateExcReseller']);
    Route::get('/master/exclusive-reseller/delete/{id}', [ExclusiveResellerController::class, 'deleteExcReseller']);

    //=============================INVENTORY EXCLUSIVE RESELLER LIST 00402==========================
    Route::get('/master/exclusive-reseller-inventory/table', [InventoryExclusiveResellerController::class, 'listInventory']);
    Route::get('/master/exclusive-reseller-inventory/load-exclusive-reseller', [InventoryExclusiveResellerController::class, 'loadExcReseller']);
    Route::get('/master/exclusive-reseller-inventory/load/{exc_resseller_id}', [InventoryExclusiveResellerController::class, 'loadInventory']);
    Route::get('/master/exclusive-reseller-inventory/load-log-stock/{exc_resseller_id}/{product_id}', [InventoryExclusiveResellerController::class, 'loadLogStock']);
    Route::get('/master/exclusive-reseller-inventory/load-log-stock-supplier/{exc_resseller_id}/{product_supplier_id}', [InventoryExclusiveResellerController::class, 'loadLogStockSupplier']);

    //=============================DO OUTLET 00403==========================
    Route::get('/process/delivery-order-exclusive-reseller/table', [DeliveryOrderExcResellerController::class, 'listDOExcReseller']);
    Route::get('/process/delivery-order-exclusive-reseller/load/{start_date}/{end_date}', [DeliveryOrderExcResellerController::class, 'loadDOExcReseller']);
    Route::get('/process/delivery-order-exclusive-reseller/load-product', [DeliveryOrderExcResellerController::class, 'loadProduct']);
    Route::post('/process/delivery-order-exclusive-reseller/add', [DeliveryOrderExcResellerController::class, 'addDOExcReseller']);
    Route::post('/process/delivery-order-exclusive-reseller/update', [DeliveryOrderExcResellerController::class, 'updateDOExcReseller']);
    Route::get('/process/delivery-order-exclusive-reseller/delete/{id}', [DeliveryOrderExcResellerController::class, 'deleteDOExcReseller']);
    Route::get('/process/delivery-order-exclusive-reseller/get-label/{label_product}', [DeliveryOrderExcResellerController::class, 'getProductLabel']);

    //=============================SALES ORDER EXCLUSIVE RESELLER 00404==========================
    Route::get('/process/sales-order-exclusive-reseller/table', [SalesOrderExcResellerController::class, 'listSO']);
    Route::get('/process/sales-order-exclusive-reseller/load/{start_date}/{end_date}', [SalesOrderExcResellerController::class, 'loadSO']);
    Route::get('/process/sales-order-exclusive-reseller/load-product', [SalesOrderExcResellerController::class, 'loadProduct']);
    Route::get('/process/sales-order-exclusive-reseller/get-label/{product_label}', [SalesOrderExcResellerController::class, 'getProductLabel']);
    Route::post('/process/sales-order-exclusive-reseller/add', [SalesOrderExcResellerController::class, 'addSO']);
    Route::post('/process/sales-order-exclusive-reseller/update', [SalesOrderExcResellerController::class, 'updateSO']);
    Route::get('/process/sales-order-exclusive-reseller/delete/{so_id}', [SalesOrderExcResellerController::class, 'deleteSO']);
    Route::post('/process/sales-order-exclusive-reseller-payment/add', [SalesOrderExcResellerController::class, 'addPayment']);
    Route::get('/process/sales-order-exclusive-reseller-payment/load/{so_id}', [SalesOrderExcResellerController::class, 'loadPayment']);
    Route::get('/process/sales-order-exclusive-reseller-print/{so_id}', [SalesOrderExcResellerController::class, 'printSalesOrder']);

    //=============================SALES ORDER OUTLET 00601==========================
    Route::get('/process/sales-order-outlet/table', [SalesOrderOutletController::class, 'listSO']);
    Route::get('/process/sales-order-outlet/load/{start_date}/{end_date}', [SalesOrderOutletController::class, 'loadSO']);
    Route::get('/process/sales-order-outlet/load-product', [SalesOrderOutletController::class, 'loadProduct']);
    Route::get('/process/sales-order-outlet/get-label/{product_label}', [SalesOrderOutletController::class, 'getProductLabel']);
    Route::post('/process/sales-order-outlet/add', [SalesOrderOutletController::class, 'addSO']);
    Route::post('/process/sales-order-outlet/update', [SalesOrderOutletController::class, 'updateSO']);
    Route::get('/process/sales-order-outlet/delete/{so_id}', [SalesOrderOutletController::class, 'deleteSO']);
    Route::post('/process/sales-order-outlet-payment/add', [SalesOrderOutletController::class, 'addPayment']);
    Route::get('/process/sales-order-outlet-payment/load/{so_id}', [SalesOrderOutletController::class, 'loadPayment']);
    Route::get('/process/sales-order-outlet-print/{so_id}', [SalesOrderOutletController::class, 'printSalesOrder']);

    //=============================SALES ORDER 00501==========================
    Route::get('/process/sales-order/table', [SalesOrderController::class, 'listSO']);
    Route::get('/process/sales-order/load/{start_date}/{end_date}', [SalesOrderController::class, 'loadSO']);
    Route::get('/process/sales-order/load-product', [SalesOrderController::class, 'loadProduct']);
    Route::get('/process/sales-order/get-label/{product_label}', [SalesOrderController::class, 'getProductLabel']);
    Route::post('/process/sales-order/add', [SalesOrderController::class, 'addSO']);
    Route::post('/process/sales-order/justadd-customer', [SalesOrderController::class, 'justAddCustomer']);
    Route::post('/process/sales-order/update', [SalesOrderController::class, 'updateSO']);
    Route::get('/process/sales-order/delete/{so_id}', [SalesOrderController::class, 'deleteSO']);
    Route::post('/process/sales-order-payment/add', [SalesOrderController::class, 'addPayment']);
    Route::get('/process/sales-order-payment/load/{so_id}', [SalesOrderController::class, 'loadPayment']);
    Route::get('/process/sales-order-print/{so_id}', [SalesOrderController::class, 'printSalesOrder']);
    Route::get('/process/sales-order-print-struk/{so_id}', [SalesOrderController::class, 'printSalesOrderStruk']);

    //=============================SALES ORDER PO 00502==========================
    Route::get('/process/sales-order-po/table', [SalesOrderPoController::class, 'listSO']);
    Route::get('/process/sales-order-po/load/{start_date}/{end_date}', [SalesOrderPoController::class, 'loadSO']);
    Route::get('/process/sales-order-po/load-product', [SalesOrderPoController::class, 'loadProduct']);
    Route::get('/process/sales-order-po/get-label/{product_label}', [SalesOrderPoController::class, 'getProductLabel']);
    Route::post('/process/sales-order-po/add', [SalesOrderPoController::class, 'addSO']);
    Route::post('/process/sales-order-po/update', [SalesOrderPoController::class, 'updateSO']);
    Route::get('/process/sales-order-po/delete/{so_id}', [SalesOrderPoController::class, 'deleteSO']);
    Route::post('/process/sales-order-po-payment/add', [SalesOrderPoController::class, 'addPayment']);
    Route::get('/process/sales-order-po-payment/load/{so_id}', [SalesOrderPoController::class, 'loadPayment']);
    Route::get('/process/sales-order-po-print/{so_id}', [SalesOrderPoController::class, 'printSalesOrder']);

    //=============================SALES ORDER ENDORSE 00601==========================
    Route::get('/process/sales-order-endorse/table', [SalesOrderEndorseController::class, 'listSO']);
    Route::get('/process/sales-order-endorse/load/{start_date}/{end_date}', [SalesOrderEndorseController::class, 'loadSO']);
    Route::get('/process/sales-order-endorse/load-product', [SalesOrderEndorseController::class, 'loadProduct']);
    Route::get('/process/sales-order-endorse/get-label/{product_label}', [SalesOrderEndorseController::class, 'getProductLabel']);
    Route::post('/process/sales-order-endorse/add', [SalesOrderEndorseController::class, 'addSO']);
    Route::post('/process/sales-order-endorse/update', [SalesOrderEndorseController::class, 'updateSO']);
    Route::get('/process/sales-order-endorse/delete/{so_id}', [SalesOrderEndorseController::class, 'deleteSO']);
    Route::post('/process/sales-order-endorse-payment/add', [SalesOrderEndorseController::class, 'addPayment']);
    Route::get('/process/sales-order-endorse-payment/load/{so_id}', [SalesOrderEndorseController::class, 'loadPayment']);
    Route::get('/process/sales-order-endorse-print/{so_id}', [SalesOrderEndorseController::class, 'printSalesOrder']);

    //=============================SALES ORDER SOSMED 00602==========================
    Route::get('/process/sales-order-sosmed/table', [SalesOrderMedsosController::class, 'listSO']);
    Route::get('/process/sales-order-sosmed/load/{start_date}/{end_date}', [SalesOrderMedsosController::class, 'loadSO']);
    Route::get('/process/sales-order-sosmed/load-product', [SalesOrderMedsosController::class, 'loadProduct']);
    Route::get('/process/sales-order-sosmed/get-label/{product_label}', [SalesOrderMedsosController::class, 'getProductLabel']);
    Route::post('/process/sales-order-sosmed/add', [SalesOrderMedsosController::class, 'addSO']);
    Route::post('/process/sales-order-sosmed/update', [SalesOrderMedsosController::class, 'updateSO']);
    Route::get('/process/sales-order-sosmed/delete/{so_id}', [SalesOrderMedsosController::class, 'deleteSO']);
    Route::post('/process/sales-order-sosmed-payment/add', [SalesOrderMedsosController::class, 'addPayment']);
    Route::get('/process/sales-order-sosmed-payment/load/{so_id}', [SalesOrderMedsosController::class, 'loadPayment']);
    Route::get('/process/sales-order-sosmed-print/{so_id}', [SalesOrderMedsosController::class, 'printSalesOrder']);

    //=============================SALES ORDER SPECIAL GIFT 00603==========================
    Route::get('/process/sales-order-special-gift/table', [SalesOrderSpecialGiftController::class, 'listSO']);
    Route::get('/process/sales-order-special-gift/load/{start_date}/{end_date}', [SalesOrderSpecialGiftController::class, 'loadSO']);
    Route::get('/process/sales-order-special-gift/load-product', [SalesOrderSpecialGiftController::class, 'loadProduct']);
    Route::get('/process/sales-order-special-gift/get-label/{product_label}', [SalesOrderSpecialGiftController::class, 'getProductLabel']);
    Route::post('/process/sales-order-special-gift/add', [SalesOrderSpecialGiftController::class, 'addSO']);
    Route::post('/process/sales-order-special-gift/update', [SalesOrderSpecialGiftController::class, 'updateSO']);
    Route::get('/process/sales-order-special-gift/delete/{so_id}', [SalesOrderSpecialGiftController::class, 'deleteSO']);
    Route::post('/process/sales-order-special-gift-payment/add', [SalesOrderSpecialGiftController::class, 'addPayment']);
    Route::get('/process/sales-order-special-gift-payment/load/{so_id}', [SalesOrderSpecialGiftController::class, 'loadPayment']);
    Route::get('/process/sales-order-special-gift-print/{so_id}', [SalesOrderSpecialGiftController::class, 'printSalesOrder']);

    //=============================SALES ORDER OWNER 00604==========================
    Route::get('/process/sales-order-owner/table', [SalesOrderOwnerController::class, 'listSO']);
    Route::get('/process/sales-order-owner/load/{start_date}/{end_date}', [SalesOrderOwnerController::class, 'loadSO']);
    Route::get('/process/sales-order-owner/load-product', [SalesOrderOwnerController::class, 'loadProduct']);
    Route::get('/process/sales-order-owner/get-label/{product_label}', [SalesOrderOwnerController::class, 'getProductLabel']);
    Route::post('/process/sales-order-owner/add', [SalesOrderOwnerController::class, 'addSO']);
    Route::post('/process/sales-order-owner/update', [SalesOrderOwnerController::class, 'updateSO']);
    Route::get('/process/sales-order-owner/delete/{so_id}', [SalesOrderOwnerController::class, 'deleteSO']);
    Route::post('/process/sales-order-owner-payment/add', [SalesOrderOwnerController::class, 'addPayment']);
    Route::get('/process/sales-order-owner-payment/load/{so_id}', [SalesOrderOwnerController::class, 'loadPayment']);
    Route::get('/process/sales-order-owner-print/{so_id}', [SalesOrderOwnerController::class, 'printSalesOrder']);

    //=============================PRODUCT 01101==========================
    Route::get('/master/product/table', [ProductController::class, 'listProduct']);
    Route::get('/master/product/load', [ProductController::class, 'loadProduct']);
    Route::post('/master/product/add', [ProductController::class, 'addProduct']);
    Route::post('/master/product/update', [ProductController::class, 'updateProduct']);
    Route::get('/master/product/delete/{id}', [ProductController::class, 'deleteProduct']);

    //=============================INVENTORY PRODUCT LIST 01102==========================
    Route::get('/master/product-inventory/table', [InventoryProductController::class, 'listInventory']);
    Route::get('/master/product-inventory/load-product', [InventoryProductController::class, 'loadProduct']);
    Route::get('/master/product-inventory/load-log-stock/{product_id}', [InventoryProductController::class, 'loadLogStock']);

    //=============================PRODUCT 012==========================
    Route::get('/master/product-supplier/table', [ProductSupplierController::class, 'listProductSupplier']);
    Route::get('/master/product-supplier/load', [ProductSupplierController::class, 'loadProductSupplier']);
    Route::post('/master/product-supplier/add', [ProductSupplierController::class, 'addProductSupplier']);
    Route::post('/master/product-supplier/update', [ProductSupplierController::class, 'updateProductSupplier']);
    Route::get('/master/product-supplier/delete/{id}', [ProductSupplierController::class, 'deleteProductSupplier']);

    //=============================INVENTORY PRODUCT SUPPLIER LIST 01202==========================
    Route::get('/master/product-supplier-inventory/table', [InventoryProductSupplierController::class, 'listInventory']);
    Route::get('/master/product-supplier-inventory/load-product', [InventoryProductSupplierController::class, 'loadProduct']);
    Route::get('/master/product-supplier-inventory/load-log-stock/{product_id}', [InventoryProductSupplierController::class, 'loadLogStock']);

    //=============================CASH OUT 014==========================
    Route::get('/process/cash-out/table', [CashOutController::class, 'cashOutTable']);
    Route::get('/process/cash-out/load/{start_date}/{end_date}', [CashOutController::class, 'loadCashOut']);
    Route::post('/process/cash-out/add', [CashOutController::class, 'addCashOut']);
    Route::post('/process/cash-out/update', [CashOutController::class, 'updateCashOut']);
    Route::get('/process/cash-out/delete/{id}', [CashOutController::class, 'deleteCashOut']);

    //=============================CASH IN 016==========================
    Route::get('/process/cash-in/table', [CashInController::class, 'cashInTable']);
    Route::get('/process/cash-in/load/{start_date}/{end_date}', [CashInController::class, 'loadCashIn']);
    Route::post('/process/cash-in/add', [CashInController::class, 'addCashIn']);
    Route::post('/process/cash-in/update', [CashInController::class, 'updateCashIn']);
    Route::get('/process/cash-in/delete/{id}', [CashInController::class, 'deleteCashIn']);

    //=============================STOCK OPNAME SCHEDULE 01501==========================
    Route::get('/process/stock-opname-schedule/tabel', [StockOpnameScheduleController::class, 'listOpnameSchedule']);
    Route::get('/process/stock-opname-schedule/load', [StockOpnameScheduleController::class, 'loadOpnameSchedule']);
    Route::post('/process/stock-opname-schedule/add', [StockOpnameScheduleController::class, 'addOpnameSchedule']);
    Route::post('/process/stock-opname-schedule/update', [StockOpnameScheduleController::class, 'updateOpnameSchedule']);
    Route::get('/process/stock-opname-schedule/delete/{id}', [StockOpnameScheduleController::class, 'deleteOpnameSchedule']);
    Route::get('/process/stock-opname-schedule/close/{id}', [StockOpnameScheduleController::class, 'closeOpnameSchedule']);

    //=============================STOCK OPNAME PRODUCT 01502==========================
    Route::get('/process/stock-opname-product/tabel', [StockOpnameProductController::class, 'listStockOpname']);
    Route::get('/process/stock-opname-product/load-opname', [StockOpnameProductController::class, 'loadData']);
    Route::get('/process/stock-opname-product/load', [StockOpnameProductController::class, 'loadProduct']);
    Route::get('/process/stock-opname-product/load-opname-item/{id}', [StockOpnameProductController::class, 'loadProductEdit']);
    Route::post('/process/stock-opname-product/add', [StockOpnameProductController::class, 'addOpnameProduct']);
    Route::get('/process/stock-opname-product/delete/{id}', [StockOpnameProductController::class, 'deleteOpname']);

    //=============================PO SUPPLIER 021==========================
    Route::get('/process/purchase-order-supplier/table', [PurchaseOrderSupplierController::class, 'listPOSupplier']);
    Route::get('/process/purchase-order-supplier/load/{start_date}/{end_date}', [PurchaseOrderSupplierController::class, 'loadPOSupplier']);
    Route::get('/process/purchase-order-supplier/load-product', [PurchaseOrderSupplierController::class, 'loadProduct']);
    Route::post('/process/purchase-order-supplier/add', [PurchaseOrderSupplierController::class, 'addPOSupplier']);
    Route::post('/process/purchase-order-supplier/update', [PurchaseOrderSupplierController::class, 'updatePOSupplier']);
    Route::get('/process/purchase-order-supplier/delete/{id}', [PurchaseOrderSupplierController::class, 'deletePOSupplier']);

    //=============================PO SUPPLIER 022==========================
    Route::get('/process/purchase-order-material/table', [PurchaseOrderMaterialController::class, 'listPOMaterial']);
    Route::get('/process/purchase-order-material/load/{start_date}/{end_date}', [PurchaseOrderMaterialController::class, 'loadPOMaterial']);
    Route::get('/process/purchase-order-material/load-product', [PurchaseOrderMaterialController::class, 'loadProductSupplier']);
    Route::post('/process/purchase-order-material/add', [PurchaseOrderMaterialController::class, 'addPOMaterial']);
    Route::post('/process/purchase-order-material/update', [PurchaseOrderMaterialController::class, 'updatePOMaterial']);
    Route::get('/process/purchase-order-material/delete/{id}', [PurchaseOrderMaterialController::class, 'deletePOMaterial']);

    //=============================PO CUSTOMER 021==========================
    Route::get('/process/purchase-order-customer/table', [PurchaseOrderCustomerController::class, 'listPOCustomer']);
    Route::get('/process/purchase-order-customer/load/{start_date}/{end_date}', [PurchaseOrderCustomerController::class, 'loadPOCustomer']);
    Route::get('/process/purchase-order-customer/load-product', [PurchaseOrderCustomerController::class, 'loadProduct']);
    Route::post('/process/purchase-order-customer/add', [PurchaseOrderCustomerController::class, 'addPOCustomer']);
    Route::post('/process/purchase-order-customer/update', [PurchaseOrderCustomerController::class, 'updatePOCustomer']);
    Route::get('/process/purchase-order-customer/delete/{id}', [PurchaseOrderCustomerController::class, 'deletePOCustomer']);

    //=============================PRODUCTION 041==========================
    Route::get('/process/production/table', [ProductionController::class, 'listPOSupplier']);
    Route::get('/process/production/load/{start_date}/{end_date}', [ProductionController::class, 'loadProduction']);
    Route::get('/process/production/load-product-supplier', [ProductionController::class, 'loadProductSupplier']);
    Route::post('/process/production/add', [ProductionController::class, 'addProduction']);
    Route::post('/process/production/update', [ProductionController::class, 'updateProduction']);
    Route::get('/process/production/delete/{id}', [ProductionController::class, 'deleteProduction']);

    //=============================RECEIVING PRODUCT 081==========================
    Route::get('/process/receiving-product/table', [ReceivingProductController::class, 'listRecProduct']);
    Route::get('/process/receiving-product/load/{start_date}/{end_date}', [ReceivingProductController::class, 'loadRecProduct']);
    Route::get('/process/receiving-product/get-total-receive/{po_supplier_item_id}', [ReceivingProductController::class, 'getTotalReceive']);
    Route::post('/process/receiving-product/add', [ReceivingProductController::class, 'addRecProduct']);
    Route::get('/process/receiving-product/get-label/{label_product}', [ReceivingProductController::class, 'getProductLabel']);
    // Route::post('/process/production/update', [ProductionController::class, 'updateProduction']);
    Route::get('/process/receiving-product/delete/{id}', [ReceivingProductController::class, 'deleteRecProduct']);

    //=============================RECEIVING PRODUCT 082==========================
    Route::get('/process/receiving-product-supplier/table', [ReceivingProductSupplierController::class, 'listRecProduct']);
    Route::get('/process/receiving-product-supplier/load/{start_date}/{end_date}', [ReceivingProductSupplierController::class, 'loadRecProduct']);
    Route::get('/process/receiving-product-supplier/get-total-receive/{po_supplier_item_id}', [ReceivingProductSupplierController::class, 'getTotalReceive']);
    Route::post('/process/receiving-product-supplier/add', [ReceivingProductSupplierController::class, 'addRecProduct']);
    Route::get('/process/receiving-product-supplier/get-label/{label_product}', [ReceivingProductSupplierController::class, 'getProductLabel']);
    // Route::post('/process/production/update', [ProductionController::class, 'updateProduction']);
    Route::get('/process/receiving-product-supplier/delete/{id}', [ReceivingProductSupplierController::class, 'deleteRecProduct']);

    //=============================REPORT SALES ORDER 09201==========================
    Route::get('/report/sales-order/table', [ReportSalesOrderController::class, 'reportSOTable']);
    Route::get('/report/sales-order/load/{start_date}/{end_date}', [ReportSalesOrderController::class, 'loadReportSO']);

    //=============================REPORT SALES ORDER EXCLUSIVE RESELLER 09202==========================
    Route::get('/report/sales-order-exclusive-reseller/table', [ReportSalesOrderExcResellerController::class, 'reportSOTable']);
    Route::get('/report/sales-order-exclusive-reseller/load/{start_date}/{end_date}', [ReportSalesOrderExcResellerController::class, 'loadReportSO']);

    //=============================REPORT SALES ORDER ENDORSE 09203==========================
    Route::get('/report/sales-order-endorse/table', [ReportSalesOrderEndorseController::class, 'reportSOTable']);
    Route::get('/report/sales-order-endorse/load/{start_date}/{end_date}', [ReportSalesOrderEndorseController::class, 'loadReportSO']);

    //=============================REPORT SALES ORDER ENDORSE 09208==========================
    Route::get('/report/sales-order-special-gift/table', [ReportSalesOrderSpesialGiftController::class, 'reportSOTable']);
    Route::get('/report/sales-order-special-gift/load/{start_date}/{end_date}', [ReportSalesOrderSpesialGiftController::class, 'loadReportSO']);

    //=============================REPORT SALES ORDER MEDSOS 09204==========================
    Route::get('/report/sales-order-medsos/table', [ReportSalesOrderMedsosController::class, 'reportSOTable']);
    Route::get('/report/sales-order-medsos/load/{start_date}/{end_date}', [ReportSalesOrderMedsosController::class, 'loadReportSO']);

    //=============================REPORT SALES ORDER OUTLET 09205==========================
    Route::get('/report/sales-order-outlet/table', [ReportSalesOrderOutletController::class, 'reportSOTable']);
    Route::get('/report/sales-order-outlet/load/{start_date}/{end_date}', [ReportSalesOrderOutletController::class, 'loadReportSO']);

    //=============================REPORT SALES ORDER OWNER 09206==========================
    Route::get('/report/sales-order-owner/table', [ReportSalesOrderOwnerController::class, 'reportSOTable']);
    Route::get('/report/sales-order-owner/load/{start_date}/{end_date}', [ReportSalesOrderOwnerController::class, 'loadReportSO']);

    //=============================REPORT SALES ORDER OWNER 09207==========================
    Route::get('/report/general-ledger/table', [ReportGeneralLedgerController::class, 'reportTable']);
    Route::get('/report/general-ledger/load/{start_date}/{end_date}', [ReportGeneralLedgerController::class, 'loadReport']);

    //=============================GENERATE LABEL 091==========================
    Route::get('/master/generate-label-product/table', [LabelProductController::class, 'generateLabelProduct']);
    Route::get('/master/generate-label-product/load/{product_id}', [LabelProductController::class, 'loadLabel']);
    Route::post('/master/generate-label-product/add', [LabelProductController::class, 'addGenerateLabel']);
    Route::post('/master/generate-label-product/update', [LabelProductController::class, 'updateGenerateLabel']);
    Route::get('/master/print-generate-label-product/{print_id}', [LabelProductController::class, 'printLabel']);
    // Route::get('/process/purchase-order-supplier/load/{start_date}/{end_date}', [PurchaseOrderSupplierController::class, 'loadPOSupplier']);
    // Route::get('/process/purchase-order-supplier/load-product', [PurchaseOrderSupplierController::class, 'loadProduct']);
    // Route::post('/process/purchase-order-supplier/add', [PurchaseOrderSupplierController::class, 'addPOSupplier']);
    // Route::post('/process/purchase-order-supplier/update', [PurchaseOrderSupplierController::class, 'updatePOSupplier']);
    // Route::get('/process/purchase-order-supplier/delete/{id}', [PurchaseOrderSupplierController::class, 'deletePOSupplier']);

    //=============================SUPERUSER USER 997==========================
    Route::get('/master/broadcast-email/table', [BroadcastEmailController::class, 'emailTable']);
    Route::get('/master/broadcast-email/load', [BroadcastEmailController::class, 'loadBroadcast']);
    Route::get('/master/broadcast-email/load-customer', [BroadcastEmailController::class, 'loadCustomer']);
    Route::post('/master/broadcast-email/add', [BroadcastEmailController::class, 'addBroadcast']);
    Route::post('/master/broadcast-email/update', [BroadcastEmailController::class, 'updateBroadcast']);
    Route::get('/master/broadcast-email/{id}', [BroadcastEmailController::class, 'deleteBroadcast']);
    Route::post('/master/broadcast-email/do-broadcast', [BroadcastEmailController::class, 'doBroadcast']);

    //=============================SUPERUSER USER 997==========================
    Route::get('/users-superuser/table', [UserSuperuserController::class, 'listUser']);
    Route::get('/users-superuser/load', [UserSuperuserController::class, 'loadUser']);
    Route::post('/users-superuser/add', [UserSuperuserController::class, 'addUser']);
    Route::post('/users-superuser/update', [UserSuperuserController::class, 'updateUser']);
    Route::post('/users-superuser/update-profile', [UserSuperuserController::class, 'updateProfile']);
    Route::post('/users-superuser/update-password', [UserSuperuserController::class, 'updatePassword']);
    Route::get('/users-superuser/delete/{id}', [UserSuperuserController::class, 'deleteUser']);

    //=============================USER 998==========================
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
    Route::get('/setting/numbering-form-counter/get-by-type/{type}', [NumberingFormController::class, 'getNumberingFormByType']);


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
    Route::post('/master/add-role/add', [RoleController::class, 'onlyAddRole']);
    Route::post('/master/add-role/update', [RoleController::class, 'onlyUpdateRole']);
    Route::get('/master/role/delete/{id}', [RoleController::class, 'deleteRole']);

    //=============================ROLE 99903==========================
    Route::get('/master/role-superuser/table', [RoleSuperuserController::class, 'listRole']);
    Route::get('/master/role-superuser/load', [RoleSuperuserController::class, 'loadRole']);
    Route::post('/master/role-superuser/add', [RoleSuperuserController::class, 'addRole']);
    Route::post('/master/role-superuser/update', [RoleSuperuserController::class, 'updateRole']);
    Route::post('/master/add-role-superuser/add', [RoleSuperuserController::class, 'onlyAddRole']);
    Route::post('/master/add-role-superuser/update', [RoleSuperuserController::class, 'onlyUpdateRole']);
    Route::get('/master/role-superuser/delete/{id}', [RoleSuperuserController::class, 'deleteRole']);

});
