<?php

namespace App\Http\Controllers\Process;

use Throwable;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Models\Master\PIC;
use Illuminate\Http\Request;
use App\Models\Master\Customer;
use App\Models\Part\PartCustomer;
use App\Models\Process\SalesOrder;
use Illuminate\Support\Facades\DB;
use App\Models\Master\ProcessPrice;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Process\SalesOrderItem;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\Process\ForecastController;
use App\Http\Controllers\Process\BudgetingController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Setting\NumberingFormController;
use App\Http\Controllers\Production\ProductionScheduleController;
use App\Models\MazuMaster\PaidType;

class SalesOrderController extends Controller
{
    public $MenuID = '04';
    public $objNumberingForm;
    public $objBudgeting;
    public $objForecast;
    public $generateType = 'F_SALES_ORDER';

    public function __construct()
    {
        $this->objNumberingForm = new NumberingFormController();
        $this->objBudgeting = new BudgetingController();
        $this->objForecast = new ForecastController();
        $this->objProductionSchedule = new ProductionScheduleController();
    }

    public function listSalesOrder(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        $store_id = getStoreId();
        $today = Carbon::today()->toDateString();
        $customerList = Customer::where('is_active', 1)->where('store_id', $store_id)->with('divisi')->get();
        $partCustomerList = PartCustomer::where('is_active', 1)->where('store_id', $store_id)->with('divisi', 'unit', 'part_price', 'bom.bom_item.part_customer.part_price', 'bom.bom_item.part_supplier.part_price', 'bop.bop_item')->get();
        $processPriceList = ProcessPrice::where('is_active', 1)->where('effective_date', '<=', $today)->get();
        $paidTypeList = PaidType::where('is_active', 1)->with('divisi')->get();


        return view('process.salesOrderTable', [
            'MenuID'            => $this->MenuID,
            'customerList'      => $customerList,
            'partCustomerList'  => $partCustomerList,
            'processPriceList'  => $processPriceList,
        ]);
    }

    public function loadSalesOrder($month_periode, $year_periode){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $salesOrderList = SalesOrder::where('is_active', 1)
                                        ->where('month_periode', $month_periode)
                                        ->where('year_periode', $year_periode)
                                        ->with('customer')
                                        ->orderBy('year_periode', 'desc')
                                        ->orderBy('month_periode', 'desc')
                                        ->get();

        return['data'=> $salesOrderList];
    }

    public function addSalesOrder(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        $itemList = json_decode($request->itemList, TRUE);

        DB::beginTransaction();
        try {
            $arrsuccess = 0;

            $pic = PIC::where('pic_type_id', 1)->where('customer_id', $request->customer_id)->get()->first();

            $total_price = (float)0;
            foreach($itemList as $ls){
                $total_price += $ls['total_price'];
            }

            $sales_order_id = Uuid::uuid4()->toString();

            if($request->is_process){
                $so_number = $this->objNumberingForm->GENERATE_FORM_NUMBER($this->generateType);
                $salesOrder = SalesOrder::create([
                    'sales_order_id'         => $sales_order_id,
                    'so_number'              => $so_number,
                    'so_date'                => $request->so_date,
                    'po_number_customer'     => $request->po_number_customer,
                    'po_date_customer'       => $request->po_date_customer,
                    'customer_id'            => $request->customer_id,
                    'month_periode'          => $request->month_periode,
                    'year_periode'           => $request->year_periode,
                    'total_price'            => $total_price,
                    'pic_sales_name'         => $pic ? $pic->pic_name : '',
                    'pic_sales_telephone'    => $pic ? $pic->pic_telephone : '',
                    'pic_sales_email'        => $pic ? $pic->pic_email : '',
                    'is_process'            => $request->is_process,
                    'is_draft'               => $request->is_draft,
                    'is_void'                => 0,
                    'is_active'              => 1,
                    'created_user'           => Auth::User()->employee->employee_name,
                ]);
            }else{
                $salesOrder = SalesOrder::create([
                    'sales_order_id'         => $sales_order_id,
                    'so_date'                => $request->so_date,
                    'po_number_customer'     => $request->po_number_customer,
                    'po_date_customer'       => $request->po_date_customer,
                    'customer_id'            => $request->customer_id,
                    'month_periode'            => $request->month_periode,
                    'year_periode'            => $request->year_periode,
                    'total_price'            => $total_price,
                    'pic_sales_name'         => $pic ? $pic->pic_name : '',
                    'pic_sales_telephone'    => $pic ? $pic->pic_telephone : '',
                    'pic_sales_email'        => $pic ? $pic->pic_email : '',
                    'is_process'            => $request->is_process,
                    'is_draft'               => $request->is_draft,
                    'is_void'                => 0,
                    'is_active'              => 1,
                    'created_user'           => Auth::User()->employee->employee_name,
                ]);
            }

            if($salesOrder){
                $i = 0;
                foreach($itemList as $ls){
                    $soitem_id = Uuid::uuid4()->toString();
                    $item = SalesOrderItem::create([
                        'soitem_id'             => $soitem_id,
                        'sales_order_id'        => $salesOrder->sales_order_id,
                        'part_customer_id'      => $ls['part_customer_id'],
                        'qty'                   => $ls['qty'],
                        'qtyM1'                 => $ls['qtyM1'],
                        'qtyM2'                 => $ls['qtyM2'],
                        'qtyM3'                 => $ls['qtyM3'],
                        'qtyM4'                 => $ls['qtyM4'],
                        'qtyM5'                 => $ls['qtyM5'],
                        'unit_id'               => $ls['unit_id'],
                        'price'                 => (float)$ls['price'],
                        'total_price'           => (float)$ls['total_price'],
                        'plant_id'              => $ls['plant_id'],
                        'divisi_id'             => $ls['divisi_id'],
                        'order_item'            => $i,
                    ]);
                    if($item && (int)$request->is_process === 1){
                        $this->objBudgeting->updateBudgeting($salesOrder, $item);
                        $this->objForecast->updateForecast($salesOrder, $item);
                        //$this->objProductionSchedule->createProductionSchedule($salesOrder, $item);
                    }
                    $i++;
                }
            }

            if ($salesOrder){
                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'Add sales order success.'], 200);
            } else {
                DB::rollback();
                return response()->json(['status' => 'Error', 'message' => 'Add sales order failled, with a part error.' ], 202);
            }
        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }

    }

    function getSalesOrder($sales_order_id){
        $salesOrder = SalesOrder::with('customer', 'so_items', 'so_items.partCustomer', 'so_items.partCustomer.bom.bom_item.part_supplier.part_price', 'so_items.partCustomer.bom.bom_item.part_customer.part_price', 'so_items.partCustomer.bop.bop_item')->where('sales_order_id', $sales_order_id)->get()->first();

        return['data'=> $salesOrder];
    }

    function updateSalesOrder(Request $request){
        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        $itemList = json_decode($request->itemList, TRUE);

        DB::beginTransaction();
        try {

            $total_price = (float)0;
            foreach($itemList as $ls){
                $total_price += $ls['total_price'];
            }

            $salesOrder = SalesOrder::find($request->sales_order_id);
            $so_number_existing = $salesOrder->so_number;
            if($salesOrder->is_draft && $request->is_process){
                $so_number = $so_number_existing ? $so_number_existing : $this->objNumberingForm->GENERATE_FORM_NUMBER($this->generateType);
                $salesOrder->update([
                    'so_number'             => $so_number,
                    'so_date'               => $request->so_date,
                    'po_number_customer'    => $request->po_number_customer,
                    'po_date_customer'       => $request->po_date_customer,
                    'customer_id'            => $request->customer_id,
                    'month_periode'            => $request->month_periode,
                    'year_periode'            => $request->year_periode,
                    'total_price'            => $total_price,
                    'is_process'            => $request->is_process,
                    'is_draft'               => $request->is_draft,
                    'is_void'                => 0,
                    'is_active'              => 1,
                    'updated_user'           => Auth::User()->employee->employee_name,
                ]);
            }else{
                $salesOrder->update([
                    'so_date'               => $request->so_date,
                    'po_number_customer'    => $request->po_number_customer,
                    'po_date_customer'       => $request->po_date_customer,
                    'customer_id'            => $request->customer_id,
                    'month_periode'            => $request->month_periode,
                    'year_periode'            => $request->year_periode,
                    'total_price'            => $total_price,
                    'is_process'            => $request->is_process,
                    'is_draft'               => $request->is_draft,
                    'is_void'                => 0,
                    'is_active'              => 1,
                    'updated_user'           => Auth::User()->employee->employee_name,
                ]);
            }

            if($salesOrder){
                $deletedRows = SalesOrderItem::where('sales_order_id', $salesOrder->sales_order_id)->get();
                $this->objBudgeting->justDeleteBudgetingBySalesOrder($salesOrder->sales_order_id);
                $this->objForecast->justDeleteForecastBySalesOrder($salesOrder->sales_order_id);

                foreach ($deletedRows as $ls) {
                    $ls->delete();
                }

                $i = 0;
                // dd($itemList);
                foreach($itemList as $ls){
                    $soitem_id = Uuid::uuid4()->toString();
                    $item = SalesOrderItem::create([
                        'soitem_id'             => $soitem_id,
                        'sales_order_id'        => $salesOrder->sales_order_id,
                        'part_customer_id'      => $ls['part_customer_id'],
                        'qty'                   => $ls['qty'],
                        'qtyM1'                 => (float)$ls['qtyM1'],
                        'qtyM2'                 => (float)$ls['qtyM2'],
                        'qtyM3'                 => (float)$ls['qtyM3'],
                        'qtyM4'                 => (float)$ls['qtyM4'],
                        'qtyM5'                 => (float)$ls['qtyM5'],
                        'unit_id'               => $ls['unit_id'],
                        'price'                 => (float)$ls['price'],
                        'total_price'           => (float)$ls['total_price'],
                        'plant_id'              => $ls['plant_id'],
                        'divisi_id'             => $ls['divisi_id'],
                        'order_item'            => $i,
                    ]);

                    if($item && (int)$request->is_process === 1){
                        // dd($request);
                        $this->objBudgeting->updateBudgeting($salesOrder, $item);
                        $this->objForecast->updateForecast($salesOrder, $item);
                        //$this->objProductionSchedule->createProductionSchedule($salesOrder, $item);
                    }
                    $i++;
                }
                // if($request->part_id){
                //     for ($i=0; $i<count($request->part_id); $i++ ){
                //         $soitem_id = Uuid::uuid4()->toString();
                //         $item = SalesOrderItem::create([
                //             'soitem_id'             => $soitem_id,
                //             'sales_order_id'        => $salesOrder->sales_order_id,
                //             'part_customer_id'      => $request->part_id[$i],
                //             'qty'                   => $request->qty[$i],
                //             'unit_id'               => $request->unit_id[$i],
                //             'price'                 => (float)$request->price[$i],
                //             'total_price'           => (float)$request->total_price[$i],
                //             'plant_id'              => $request->plant_id[$i],
                //             'divisi_id'             => $request->divisi_id[$i],
                //             'order_item'            => ($i + 1),
                //         ]);
                //         if($salesOrder->is_process == 1){
                //             $this->objBudgeting->updateBudgeting($salesOrder, $item);
                //         }

                //     }
                // }
            }

            if ($salesOrder){
                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'Update sales order success.'], 200);
            } else {
                DB::rollback();
                return response()->json(['status' => 'Error', 'message' => 'Update sales order failled, with a part error.' ], 202);
            }
        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }

    function cancelSalesOrder($sales_order_id){
        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $salesOrder = SalesOrder::find($sales_order_id);
            if ($salesOrder){
                $salesOrder->is_active = 1;
                $salesOrder->is_process = 0;
                $salesOrder->is_draft = 0;
                $salesOrder->is_void = 1;
                $salesOrder->update();

                $this->objBudgeting->justDeleteBudgetingBySalesOrder($salesOrder->sales_order_id);
                $this->objForecast->justDeleteForecastBySalesOrder($salesOrder->sales_order_id);

                return response()->json(['status' => 'Success', 'message' => 'cancel sales order success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'cancel sales order failed.'], 200);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }

    function deleteSalesOrder($sales_order_id){
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $salesOrder = SalesOrder::find($sales_order_id);
            if ($salesOrder){
                $salesOrder->is_active = 0;
                $salesOrder->is_process = 0;
                $salesOrder->is_draft = 0;
                $salesOrder->is_void = 0;
                $salesOrder->update();

                $this->objForecast->justDeleteBudgetingBySalesOrder($salesOrder->sales_order_id);
                $this->objForecast->justDeleteForecastBySalesOrder($salesOrder->sales_order_id);

                return response()->json(['status' => 'Success', 'message' => 'delete sales order success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'delete sales order failed.'], 200);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }

    function getSalesOrderItem($sales_order_id){
        $item = SalesOrderItem::with('partCustomer')->where('sales_order_id', $sales_order_id)->get();

        return['data'=> $item];
    }
}
