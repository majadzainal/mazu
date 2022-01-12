<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Master\NgController;
use App\Http\Controllers\Master\PicController;
use App\Http\Controllers\Master\UnitController;
use App\Http\Controllers\Master\UserController;
use App\Http\Controllers\Master\PlantController;
use App\Http\Controllers\Setting\RoleController;
use App\Http\Controllers\Master\CcmailController;
use App\Http\Controllers\Master\DayOffController;
use App\Http\Controllers\Master\DivisiController;
use App\Http\Controllers\Master\StatusController;
use App\Http\Controllers\Master\ProcessController;
use App\Http\Controllers\Part\PartLabelController;

use App\Http\Controllers\Master\CustomerController;

use App\Http\Controllers\Master\LocationController;
use App\Http\Controllers\Master\PartTypeController;
use App\Http\Controllers\Master\SupplierController;
use App\Http\Controllers\Setting\CounterController;
use App\Http\Controllers\Log\LogPartStockController;
use App\Http\Controllers\Master\WarehouseController;
use App\Http\Controllers\Part\BillProcessController;
use App\Http\Controllers\Process\ForecastController;

use App\Http\Controllers\Part\BillMaterialController;
use App\Http\Controllers\Part\PartCustomerController;
use App\Http\Controllers\Part\PartSupplierController;
use App\Http\Controllers\Process\BudgetingController;
use App\Http\Controllers\Process\InventoryController;
use App\Http\Controllers\Process\SalesOrderController;
use App\Http\Controllers\Master\ProcessPriceController;
use App\Http\Controllers\Process\StockOpnameController;
use App\Http\Controllers\Master\ProcessMachineController;
use App\Http\Controllers\Process\PurchaseOrderController;
use App\Http\Controllers\Process\StockOpnameFGController;
use App\Http\Controllers\Setting\NumberingFormController;
use App\Http\Controllers\Process\OpnameScheduleController;
use App\Http\Controllers\Process\StockOpnameWIPController;
use App\Http\Controllers\Production\DailyReportController;
use App\Http\Controllers\Process\RecPartSupplierController;
use App\Http\Controllers\Process\AddPurchaseOrderController;
use App\Http\Controllers\Production\RequestRawmatController;
use App\Http\Controllers\Process\StockOpnameRawMatController;
use App\Http\Controllers\Production\GenerateScheduleController;
use App\Http\Controllers\Production\ProductionReportController;
use App\Http\Controllers\Process\ApproverPurchaseOrderController;
use App\Http\Controllers\Production\ProductionScheduleController;
use App\Http\Controllers\Process\AdjustmentPurchaseOrderController;

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

Route::get('/master/import/divisi/list', [DivisiController::class, 'listDivisiImport']);
Route::get('/master/import/unit/list', [UnitController::class, 'listUnitImport']);
Route::get('/master/import/status-supplier/list', [StatusController::class, 'listStatusSupplierImport']);
Route::get('/master/import/status-customer/list', [StatusController::class, 'listStatusCustomerImport']);
Route::get('/master/import/warehouse/list', [WarehouseController::class, 'listWarehouseImport']);
Route::get('/master/import/plant/list', [PLantController::class, 'listPlantImport']);
Route::get('/master/import/process/list', [ProcessController::class, 'listProcessImport']);
Route::get('/master/import/pmachine/list', [ProcessMachineController::class, 'listPMachineImport']);
Route::get('/master/import/supplier/list', [SupplierController::class, 'listSupplierImport']);
Route::get('/master/import/customer/list', [CustomerController::class, 'listCustomerImport']);
Route::get('/master/import/part-supplier/list', [PartSupplierController::class, 'listPartSupplierImport']);
Route::get('/master/import/part-customer/list', [PartCustomerController::class, 'listPartCustomerImport']);

Auth::routes();

Route::group(['middleware' => 'auth'], function () {

    //=============================DASHBOARD 01 ==========================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('HOME');
    Route::get('/dashboard/load-part-supplier', [DashboardController::class, 'loadPartSupplier']);
    Route::get('/dashboard/machine-process/{plant}', [DashboardController::class, 'getMachineProcess']);

    //=============================LOCATION 0201==========================
    Route::get('/master/location/table', [LocationController::class, 'listLocation']);
    Route::get('/master/location/load', [LocationController::class, 'loadLocation']);
    Route::post('/master/location/add', [LocationController::class, 'addLocation']);
    Route::post('/master/location/update', [LocationController::class, 'updateLocation']);
    Route::get('/master/location/delete/{id}', [LocationController::class, 'deleteLocation']);

    //=============================PLANT 0203==========================
    Route::get('/master/plant/table', [PlantController::class, 'listPlant']);
    Route::get('/master/plant/load', [PlantController::class, 'loadPlant']);
    Route::post('/master/plant/add', [PlantController::class, 'addPlant']);
    Route::post('/master/plant/update', [PlantController::class, 'updatePlant']);
    Route::get('/master/plant/delete/{id}', [PlantController::class, 'deletePlant']);

    //=============================SUPPLIER 0204==========================
    Route::get('/master/supplier/table', [SupplierController::class, 'listSupplier']);
    Route::get('/master/supplier/load/{is_active}', [SupplierController::class, 'loadSupplier']);
    Route::post('/master/supplier/add', [SupplierController::class, 'addSupplier']);
    Route::post('/master/supplier/update', [SupplierController::class, 'updateSupplier']);
    Route::get('/master/supplier/delete/{id}', [SupplierController::class, 'deleteSupplier']);
    Route::get('/master/supplier/activate/{id}', [SupplierController::class, 'activateSupplier']);
    Route::post('/master/supplier/import', [SupplierController::class, 'importSupplier']);
    Route::get('/master/supplier/template-download', function () {
        return response()->download($this->path."MASTER UPLOAD SUPPLIER.xlsx");
    });

    //=============================CUSTOMER 0205==========================
    Route::get('/master/customer/table', [CustomerController::class, 'listCustomer']);
    Route::get('/master/customer/load/{is_active}', [CustomerController::class, 'loadCustomer']);
    Route::post('/master/customer/add', [CustomerController::class, 'addCustomer']);
    Route::post('/master/customer/update', [CustomerController::class, 'updateCustomer']);
    Route::get('/master/customer/delete/{id}', [CustomerController::class, 'deleteCustomer']);
    Route::get('/master/customer/activate/{id}', [CustomerController::class, 'activateCustomer']);
    Route::post('/master/customer/import', [CustomerController::class, 'importCustomer']);
    Route::get('/master/customer/template-download', function () {
        return response()->download($this->path."MASTER UPLOAD CUSTOMER.xlsx");
    });

    //=============================PIC 0205==========================
    Route::get('/master/pic/load/{type}/{id}', [PicController::class, 'loadPic']);

    //=============================PROCCESS MACHINE 0206 ==========================
    Route::get('/master/process-machine/table', [ProcessMachineController::class, 'listProcessMachine']);
    Route::get('/master/process-machine/load', [ProcessMachineController::class, 'loadProcessMachine']);
    Route::post('/master/process-machine/add', [ProcessMachineController::class, 'addProcessMachine']);
    Route::post('/master/process-machine/update', [ProcessMachineController::class, 'updateProcessMachine']);
    Route::get('/master/process-machine/delete/{id}', [ProcessMachineController::class, 'deleteProcessMachine']);

    //=============================PART STATUS 0207==========================
    Route::get('/master/part-status/table', [StatusController::class, 'listStatus']);
    Route::get('/master/status/load/{status_type}', [StatusController::class, 'loadStatus']);
    Route::post('/master/status/add', [StatusController::class, 'addStatus']);
    Route::post('/master/status/update', [StatusController::class, 'updateStatus']);
    Route::get('/master/status/delete/{id}', [StatusController::class, 'deleteStatus']);

    //=============================DIVISI 0208==========================
    Route::get('/master/divisi/table', [DivisiController::class, 'listDivisi']);
    Route::get('/master/divisi/load/{part_type}', [DivisiController::class, 'loadDivisi']);
    Route::post('/master/divisi/update', [DivisiController::class, 'updateDivisi']);
    Route::post('/master/divisi/add', [DivisiController::class, 'addDivisi']);
    Route::get('/master/divisi/delete/{id}', [DivisiController::class, 'deleteDivisi']);

    Route::get('/master/part-type/load', [PartTypeController::class, 'loadPartType']);
    Route::post('/master/part-type/update', [PartTypeController::class, 'updatePartType']);
    Route::post('/master/part-type/add', [PartTypeController::class, 'addPartType']);
    Route::get('/master/part-type/delete/{id}', [PartTypeController::class, 'deletePartType']);

    //=============================UNIT 0209==========================
    Route::get('/master/unit/table', [UnitController::class, 'listUnit']);
    Route::get('/master/unit/load', [UnitController::class, 'loadUnit']);
    Route::post('/master/unit/add', [UnitController::class, 'addUnit']);
    Route::get('/master/unit/delete/{id}', [UnitController::class, 'deleteUnit']);

    //=============================PROCESS 0210==========================
    Route::get('/master/process/table', [ProcessController::class, 'listProcess']);
    Route::get('/master/process/load', [ProcessController::class, 'loadProcess']);
    Route::post('/master/process/add', [ProcessController::class, 'addProcess']);
    Route::post('/master/process/update', [ProcessController::class, 'updateProcess']);
    Route::get('/master/process/delete/{id}', [ProcessController::class, 'deleteProcess']);

    //=============================PROCESS PRICE 0211==========================
    Route::get('/master/process-price/table', [ProcessPriceController::class, 'listProcessPrice']);
    Route::get('/master/process-price/load', [ProcessPriceController::class, 'loadProcessPrice']);
    Route::get('/master/process-price/get/{process_id}/{pmachine_id}', [ProcessPriceController::class, 'getProcessPrice']);
    Route::get('/master/process-price/get/{process_id}/{pmachine_id}/{date}', [ProcessPriceController::class, 'getProcessPriceActive']);
    Route::get('/master/process-price/delete/{process_id}/{pmachine_id}', [ProcessPriceController::class, 'deleteProcessPrice']);
    Route::post('/master/process-price/add', [ProcessPriceController::class, 'addProcessPrice']);
    Route::post('/master/process-price/update', [ProcessPriceController::class, 'updateProcessPrice']);

    //=============================WAREHOUSE 0212==========================
    Route::get('/master/warehouse/table', [WarehouseController::class, 'listWarehouse']);
    Route::get('/master/warehouse/load', [WarehouseController::class, 'loadWarehouse']);
    Route::post('/master/warehouse/add', [WarehouseController::class, 'addWarehouse']);
    Route::post('/master/warehouse/update', [WarehouseController::class, 'updateWarehouse']);
    Route::get('/master/warehouse/delete/{id}', [WarehouseController::class, 'deleteWarehouse']);

    //=============================NG 0213==========================
    Route::get('/master/ng/table', [NgController::class, 'listNg']);
    Route::get('/master/ng/load', [NgController::class, 'loadNg']);
    Route::post('/master/ng/add', [NgController::class, 'addNg']);
    Route::post('/master/ng/update', [NgController::class, 'updateNg']);
    Route::get('/master/ng/delete/{id}', [NgController::class, 'deleteNg']);

    //=============================DAY OFF 0214==========================
    Route::get('/master/day-off/table', [DayOffController::class, 'listDayOff']);
    Route::get('/master/day-off/load/{year}', [DayOffController::class, 'loadDayOff']);
    Route::post('/master/day-off/add', [DayOffController::class, 'addDayOff']);
    Route::post('/master/day-off/update', [DayOffController::class, 'updateDayOff']);
    Route::get('/master/day-off/delete/{id}', [DayOffController::class, 'deleteDayOff']);

    //=============================CC MAIL 0215==========================
    Route::get('/master/mail/table', [CcmailController::class, 'listMail']);
    Route::get('/master/mail/load', [CcmailController::class, 'loadMail']);
    Route::post('/master/mail/add', [CcmailController::class, 'addMail']);
    Route::post('/master/mail/update', [CcmailController::class, 'updateMail']);
    Route::get('/master/mail/delete/{id}', [CcmailController::class, 'deleteMail']);

    //=============================PART SUPPLIER 0301==========================
    Route::get('/part-supplier/table', [PartSupplierController::class, 'listPartSupplier']);
    Route::get('/part-supplier/load', [PartSupplierController::class, 'loadPartSupplier']);
    Route::post('/part-supplier/add', [PartSupplierController::class, 'addPartSupplier']);
    Route::post('/part-supplier/update', [PartSupplierController::class, 'updatePartSupplier']);
    Route::get('/part-supplier/delete/{id}', [PartSupplierController::class, 'deletePartSupplier']);
    Route::post('/part-supplier/import', [PartSupplierController::class, 'importPartSupplier']);
    Route::get('/part-supplier/template-download', function () {
        return response()->download($this->path."MASTER UPLOAD SUPPLIER PART.xlsx");
    });

    //=============================PART CUSTOMER 0302==========================
    Route::get('/part-customer/table', [PartCustomerController::class, 'listPartCustomer']);
    Route::get('/part-customer/load', [PartCustomerController::class, 'loadPartCustomer']);
    Route::post('/part-customer/add', [PartCustomerController::class, 'addPartCustomer']);
    Route::post('/part-customer/update', [PartCustomerController::class, 'updatePartCustomer']);
    Route::get('/part-customer/delete/{id}', [PartCustomerController::class, 'deletePartCustomer']);
    Route::post('/part-customer/import', [PartCustomerController::class, 'importPartCustomer']);
    Route::get('/part-customer/loadbyid/{id}', [PartCustomerController::class, 'loadPartCustomerByCustomerId']);
    Route::get('/part-customer/template-download', function () {
        return response()->download($this->path."MASTER UPLOAD CUSTOMER PART.xlsx");
    });

    //=============================BILL OF MATERIAL 0303 ==========================
    Route::get('/bill-material/table', [BillMaterialController::class, 'listBillMaterial']);
    Route::get('/bill-material/load', [BillMaterialController::class, 'loadBillMaterial']);
    Route::post('/bill-material/add', [BillMaterialController::class, 'addBillMaterial']);
    Route::post('/bill-material/update', [BillMaterialController::class, 'updateBillMaterial']);
    Route::get('/bill-material/delete/{id}', [BillMaterialController::class, 'deleteBillMaterial']);
    Route::post('/bill-material/import', [BillMaterialController::class, 'importBillMaterial']);
    Route::get('/bill-material/getData/{id}', [BillMaterialController::class, 'getDataBillMaterial']);
    Route::get('/bill-material/getCostBOM/{id}', [BillMaterialController::class, 'getCostBillMaterial']);
    Route::get('/bill-material/template-download', function () {
        return response()->download($this->path."MASTER UPLOAD BOM.xlsx");
    });

    //=============================BILL OF PROCESS 0304 ==========================
    Route::get('/bill-process/table', [BillProcessController::class, 'listBillProcess']);
    Route::get('/bill-process/load', [BillProcessController::class, 'loadBillProcess']);
    Route::post('/bill-process/add', [BillProcessController::class, 'addBillProcess']);
    Route::post('/bill-process/update', [BillProcessController::class, 'updateBillProcess']);
    Route::get('/bill-process/delete/{id}', [BillProcessController::class, 'deleteBillProcess']);
    Route::post('/bill-process/import', [BillProcessController::class, 'importBillProcess']);
    Route::get('/bill-process/getCostBOP/{id}', [BillProcessController::class, 'getCostBillProcess']);
    Route::get('/bill-process/template-download', function () {
        return response()->download($this->path."MASTER UPLOAD BOP.xlsx");
    });

    //=============================GENERATE PART 0305 ==========================
    Route::get('/generate-part-label/table', [PartLabelController::class, 'generatePartLabel']);
    Route::get('/generate-part-label/load/{part_supplier_id}', [PartLabelController::class, 'loadPart']);
    Route::get('/generate-part-label/load-label/{part_supplier_id}', [PartLabelController::class, 'loadPartLabel']);
    Route::get('/generate-part-label/get-label/{part_label}', [PartLabelController::class, 'getPartLabel']);
    Route::get('/generate-part-label/print-label/{print_id}', [PartLabelController::class, 'printLabelSupplier']);
    Route::post('/generate-part-label/update-print', [PartLabelController::class, 'updatePartLabelPrinted']);

    //=============================PROCCESS 04==========================
    Route::get('/process/sales-order/table', [SalesOrderController::class, 'listSalesOrder']);
    Route::get('/process/sales-order/load/{month_periode}/{year_periode}', [SalesOrderController::class, 'loadSalesOrder']);
    Route::get('/process/sales-order/get/{id}', [SalesOrderController::class, 'getSalesOrder']);
    Route::post('/process/sales-order/add', [SalesOrderController::class, 'addSalesOrder']);
    Route::post('/process/sales-order/update', [SalesOrderController::class, 'updateSalesOrder']);
    Route::get('/process/sales-order/cancel/{id}', [SalesOrderController::class, 'cancelSalesOrder']);
    Route::get('/process/sales-order/delete/{id}', [SalesOrderController::class, 'deleteSalesOrder']);
    Route::get('/process/sales-order/get-item/{id}', [SalesOrderController::class, 'getSalesOrderItem']);

    //=============================PROCCESS 05==========================
    Route::get('/process/budgeting/table', [BudgetingController::class, 'listBudgeting']);
    Route::get('/process/budgeting/load/{month_periode}/{year_periode}', [BudgetingController::class, 'loadBudgeting']);
    Route::get('/process/budgeting/get/{sales_order_id}', [BudgetingController::class, 'getBudgeting']);
    Route::get('/process/budgeting/getForPO/{supplier}/{month}/{year}', [BudgetingController::class, 'getBudgetingForPO']);

    //=============================PROCCESS 051==========================
    Route::get('/process/forecast/table', [ForecastController::class, 'listForecast']);
    Route::get('/process/forecast/load/{month_periode}/{year_periode}', [ForecastController::class, 'loadForecast']);
    Route::get('/process/forecast/get/{sales_order_id}', [ForecastController::class, 'getForecast']);
    Route::get('/process/forecast/getForPO/{supplier}/{month}/{year}', [ForecastController::class, 'getForecastForPO']);

    //=============================PURCHASE ORDER 0601==========================
    Route::get('/process/purchase-order/table', [PurchaseOrderController::class, 'listPurchaseOrder']);
    Route::get('/process/purchase-order/load/{month_periode}/{year_periode}', [PurchaseOrderController::class, 'loadPurchaseOrder']);
    Route::get('/process/purchase-order/get/{id}', [PurchaseOrderController::class, 'getPurchaseOrder']);
    Route::post('/process/purchase-order/add', [PurchaseOrderController::class, 'addPurchaseOrder']);
    Route::post('/process/purchase-order/update', [PurchaseOrderController::class, 'updatePurchaseOrder']);
    Route::get('/process/purchase-order/cancel/{id}', [PurchaseOrderController::class, 'cancelPurchaseOrder']);
    Route::get('/process/purchase-order/delete/{id}', [PurchaseOrderController::class, 'deletePurchaseOrder']);
    Route::get('/process/purchase-order/print/{id}', [PurchaseOrderController::class, 'printPurchaseOrder']);
    Route::post('/process/purchase-order/send', [PurchaseOrderController::class, 'sendPurchaseOrder']);

    //=============================ADDITIONAL PURCHASE ORDER 0602==========================
    Route::get('/process/additional-purchase-order/table', [AddPurchaseOrderController::class, 'listPurchaseOrder']);
    Route::get('/process/additional-purchase-order/load/{month_periode}/{year_periode}', [AddPurchaseOrderController::class, 'loadPurchaseOrder']);
    Route::get('/process/additional-purchase-order/get/{id}', [AddPurchaseOrderController::class, 'getPurchaseOrder']);
    Route::post('/process/additional-purchase-order/add', [AddPurchaseOrderController::class, 'addPurchaseOrder']);
    Route::post('/process/additional-purchase-order/update', [AddPurchaseOrderController::class, 'updatePurchaseOrder']);
    Route::get('/process/additional-purchase-order/cancel/{id}', [AddPurchaseOrderController::class, 'cancelPurchaseOrder']);
    Route::get('/process/additional-purchase-order/delete/{id}', [AddPurchaseOrderController::class, 'deletePurchaseOrder']);

    //============================= ADJUSTMENT PURCHASE ORDER 0603==========================
    Route::get('/process/adjustment-purchase-order/table', [AdjustmentPurchaseOrderController::class, 'listAdjustPO']);
    Route::get('/process/adjustment-purchase-order/load/{month_periode}/{year_periode}', [AdjustmentPurchaseOrderController::class, 'loadAdjustPO']);
    Route::get('/process/adjustment-purchase-order/get/{id}', [AdjustmentPurchaseOrderController::class, 'getAdjustPO']);
    Route::post('/process/adjustment-purchase-order/update', [AdjustmentPurchaseOrderController::class, 'updateAdjustPO']);

    //============================= APPROVER PURCHASE ORDER 0604==========================
    Route::get('/process/approver-purchase-order/table', [ApproverPurchaseOrderController::class, 'listApproverPO']);
    Route::get('/process/approver-purchase-order/load/{month_periode}/{year_periode}', [ApproverPurchaseOrderController::class, 'loadApproverPO']);
    Route::get('/process/approver-purchase-order/get/{id}', [ApproverPurchaseOrderController::class, 'getApproverPO']);
    Route::post('/process/approver-purchase-order/update', [ApproverPurchaseOrderController::class, 'updateApproverPO']);


    //============================= APPROVER PURCHASE ORDER 07==========================
    Route::get('/process/receiving-part-supplier/table', [RecPartSupplierController::class, 'listRecPartSupplier']);
    Route::get('/process/receiving-part-supplier/load/{month_periode}/{year_periode}', [RecPartSupplierController::class, 'loadRecPartSupplier']);
    Route::post('/process/receiving-part-supplier/add', [RecPartSupplierController::class, 'addRecPartSupplier']);
    Route::get('/process/receiving-part-supplier/getbyperiode/{month_periode}/{yearperiode}', [RecPartSupplierController::class, 'getPoByPeriode']);
    Route::get('/process/receiving-part-supplier/getqtyreceive/{poitem_id}', [RecPartSupplierController::class, 'getTotalReceive']);
    Route::get('/process/receiving-part-supplier/delete/{id}', [RecPartSupplierController::class, 'deleteRecPartSupplier']);

    //=============================INVENTORY 0801==========================
    Route::get('/process/inventory/part-supplier/table', [InventoryController::class, 'listInventoryPartSupplier']);
    Route::get('/process/inventory/part-supplier/load', [InventoryController::class, 'loadInventoryPartSupplier']);

    //=============================INVENTORY 0802==========================
    Route::get('/process/inventory/part-customer/table', [InventoryController::class, 'listInventoryPartCustomer']);
    Route::get('/process/inventory/part-customer/load', [InventoryController::class, 'loadInventoryPartCustomer']);

    //=============================INVENTORY 0803==========================
    Route::get('/process/inventory/part-wip/table', [InventoryController::class, 'listInventoryPartWIP']);
    Route::get('/process/inventory/part-wip/load', [InventoryController::class, 'loadInventoryPartWIP']);

    //=============================GENERATE SCHEDULE 0907==========================
    Route::get('/production/generate-schedule', [GenerateScheduleController::class, 'initGenerateSchedule']);
    Route::get('/production/generate-schedule/get/{plant}/{month}/{year}', [GenerateScheduleController::class, 'getGenerateSchedule']);
    Route::post('/production/generate-schedule/create', [GenerateScheduleController::class, 'createGenerateSchedule']);

    //=============================PRODUCTION SCHEDULE 0901==========================
    Route::get('/production/schedule', [ProductionScheduleController::class, 'listProductionSchedule']);
    Route::get('/production/schedule/get/{plant}/{month}/{year}', [ProductionScheduleController::class, 'getProductionSchedule']);
    Route::post('/production/schedule/update', [ProductionScheduleController::class, 'updateProductionSchedule']);

    //=============================REQUEST RAW MATERIAL 0902==========================
    Route::get('/production/request-raw-material/table', [RequestRawmatController::class, 'listRequestRawmat']);
    Route::get('/production/request-raw-material/load/{month_periode}/{year_periode}', [RequestRawmatController::class, 'loadRequestRawmat']);
    Route::get('/production/request-raw-material/getRawMaterial/{plant}/{date}', [RequestRawmatController::class, 'getDataRawMaterial']);
    Route::post('/production/request-raw-material/add', [RequestRawmatController::class, 'addRequestRawmat']);
    Route::post('/production/request-raw-material/update', [RequestRawmatController::class, 'updateRequestRawmat']);
    Route::get('/production/request-raw-material/delete/{id}', [RequestRawmatController::class, 'deleteRequestRawmat']);
    Route::get('/production/request-raw-material/print/{id}', [RequestRawmatController::class, 'printRequestRawmat']);
    Route::post('/production/request-raw-material/send', [RequestRawmatController::class, 'sendRequestRawmat']);

    //=============================REQUEST RAW MATERIAL(KNOW) 0903==========================
    Route::get('/production/request-raw-material/known', [RequestRawmatController::class, 'listKnownRequestRawmat']);
    Route::post('/production/request-raw-material/update-known', [RequestRawmatController::class, 'updateKnownRequestRawmat']);
    Route::get('/production/request-raw-material/known-load/{month_periode}/{year_periode}', [RequestRawmatController::class, 'loadKnownRequestRawmat']);

    //=============================REQUEST RAW MATERIAL(APPROVE) 0904==========================
    Route::get('/production/request-raw-material/approve', [RequestRawmatController::class, 'listApproveRequestRawmat']);
    Route::post('/production/request-raw-material/update-approve', [RequestRawmatController::class, 'updateApproveRequestRawmat']);
    Route::get('/production/request-raw-material/approve-load/{month_periode}/{year_periode}', [RequestRawmatController::class, 'loadApproveRequestRawmat']);

    //=============================PRODUCTION SCHEDULE REPORT 0905==========================
    Route::get('/production/report/filter', [ProductionReportController::class, 'filterProductionReport']);
    Route::get('/production/report/get/{plant}/{from}/{to}', [ProductionReportController::class, 'getProductionReport']);
    Route::get('/production/report/print/{plant}/{from}/{to}', [ProductionReportController::class, 'printProductionReport']);

    //=============================STOCK OPNAME 1001==========================
    Route::get('/process/stock-opname-schedule/tabel', [OpnameScheduleController::class, 'listOpnameSchedule']);
    Route::get('/process/stock-opname-schedule/load', [OpnameScheduleController::class, 'loadOpnameSchedule']);
    Route::post('/process/stock-opname-schedule/add', [OpnameScheduleController::class, 'addOpnameSchedule']);
    Route::post('/process/stock-opname-schedule/update', [OpnameScheduleController::class, 'updateOpnameSchedule']);
    Route::get('/process/stock-opname-schedule/delete/{id}', [OpnameScheduleController::class, 'deleteOpnameSchedule']);
    Route::get('/process/stock-opname-schedule/close/{id}', [OpnameScheduleController::class, 'closeOpnameSchedule']);
    //=============================DAILY REPORT PRODUCTION 0906==========================
    Route::get('/production/daily-report/table', [DailyReportController::class, 'listDailyReport']);
    Route::get('/production/daily-report/load/{month_periode}/{year_periode}', [DailyReportController::class, 'loadDailyReport']);
    Route::get('/production/daily-report/getDailyReport/{plant}/{date}', [DailyReportController::class, 'getDataDailyReport']);
    Route::post('/production/daily-report/add', [DailyReportController::class, 'addDailyReport']);
    Route::post('/production/daily-report/update', [DailyReportController::class, 'updateDailyReport']);
    Route::get('/production/daily-report/delete/{id}', [DailyReportController::class, 'deleteDailyReport']);
    Route::get('/production/daily-report/print/{id}', [DailyReportController::class, 'printDailyReport']);

    //=============================STOCK OPNAME 10==========================
    Route::get('/process/stock-opname/tabel', [StockOpnameController::class, 'listStockOpname']);


     //=============================STOCK OPNAME 1002==========================
     Route::get('/process/stock-opname-finished-goods/tabel', [StockOpnameFGController::class, 'listStockOpname']);
     Route::get('/process/stock-opname-finished-goods/load-opname', [StockOpnameFGController::class, 'loadData']);
     Route::get('/process/stock-opname-finished-goods/load/{id}', [StockOpnameFGController::class, 'loadPart']);
     Route::get('/process/stock-opname-finished-goods/load-opname-item/{id}', [StockOpnameFGController::class, 'loadPartEdit']);
     Route::post('/process/stock-opname-finished-goods/add', [StockOpnameFGController::class, 'addOpnameFG']);
     Route::get('/process/stock-opname-finished-goods/delete/{id}', [StockOpnameFGController::class, 'deleteOpname']);

    //=============================STOCK OPNAME 1003==========================
     Route::get('/process/stock-opname-raw/tabel', [StockOpnameRawMatController::class, 'listStockOpname']);
     Route::get('/process/stock-opname-raw/load-opname', [StockOpnameRawMatController::class, 'loadData']);
     Route::get('/process/stock-opname-raw/load/{id}', [StockOpnameRawMatController::class, 'loadPart']);
     Route::get('/process/stock-opname-raw/load-opname-item/{id}', [StockOpnameRawMatController::class, 'loadPartEdit']);
     Route::post('/process/stock-opname-raw/add', [StockOpnameRawMatController::class, 'addOpnameRAW']);
     Route::get('/process/stock-opname-raw/delete/{id}', [StockOpnameRawMatController::class, 'deleteOpname']);

     //=============================STOCK OPNAME 1004==========================
     Route::get('/process/stock-opname-wip/tabel', [StockOpnameWIPController::class, 'listStockOpname']);
     Route::get('/process/stock-opname-wip/load-opname', [StockOpnameWIPController::class, 'loadData']);
     Route::get('/process/stock-opname-wip/load/{id}', [StockOpnameWIPController::class, 'loadPart']);
     Route::get('/process/stock-opname-wip/load-opname-item/{id}', [StockOpnameWIPController::class, 'loadPartEdit']);
     Route::post('/process/stock-opname-wip/add', [StockOpnameWIPController::class, 'addOpnameWIP']);
     Route::get('/process/stock-opname-wip/delete/{id}', [StockOpnameWIPController::class, 'deleteOpname']);


     //=============================USER 980==========================
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

    //=============================LOG PART STOCK==========================
    Route::get('/log/part-stock/get-data-part-stock/{part_supplier_id}', [LogPartStockController::class, 'getLogPartStock']);
    Route::get('/log/part-stock/get-data-part-stock-periode/{part_supplier_id}/{month}/{year}', [LogPartStockController::class, 'getLogPartStockByPeriode']);

    Route::get('/log/part-stock/get-data-part-customer-stock/{part_customer_id}', [LogPartStockController::class, 'getLogPartCustomerStock']);
    Route::get('/log/part-stock/get-data-part-customer-stock-periode/{part_customer_id}/{month}/{year}', [LogPartStockController::class, 'getLogPartCustomerStockByPeriode']);



});
