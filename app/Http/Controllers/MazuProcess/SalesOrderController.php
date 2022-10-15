<?php

namespace App\Http\Controllers\MazuProcess;

use Exception;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Models\MazuMaster\Stock;
use App\Models\MazuMaster\Store;
use App\Models\MazuMaster\Product;
use Illuminate\Support\Facades\DB;
use App\Models\MazuMaster\Customer;
use App\Models\MazuMaster\PaidType;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\MazuProcess\SalesOrder;
use App\Models\MazuMaster\LabelProduct;
use App\Models\MazuMaster\EventSchedule;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\MazuProcess\SalesOrderItem;
use App\Models\MazuProcess\SalesOrderPaid;
use App\Models\MazuMaster\CustomerCategory;
use App\Models\MazuMaster\ProductComposition;
use App\Http\Controllers\MazuMaster\StockController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Setting\NumberingFormController;
use App\Http\Controllers\MazuProcess\GeneralLedgerController;

class SalesOrderController extends Controller
{
    public $MenuID = '00501';
    public $objStock;
    public $objNumberingForm;
    public $generateType = 'F_SALES_ORDER';
    public $so_type = 7;
    public $objGl;

    public function __construct()
    {
        $this->objStock = new StockController();
        $this->objGl = new GeneralLedgerController();
        $this->objNumberingForm = new NumberingFormController();
    }

    public function listSO(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        $store_id = getStoreId();
        $isEvent = isEvent();
        $eventList = [];
        if($isEvent){
            $eventList = EventSchedule::where('is_active', 1)->where('is_closed', 0)->get();
            $customerList = Customer::where('is_active', 1)->with('store')->get();
            $custCategoryList = CustomerCategory::where('is_active', 1)->with('store')->get();
            $productList = Product::where('is_active', 1)
                        ->with('unit', 'stockWarehouse', 'stockWarehouse.warehouse', 'composition', 'composition.productSupplier')->get();

            $paidTypeList = PaidType::where('is_active', 1)->get();
        }else{
            $customerList = Customer::where('store_id', $store_id)->where('is_active', 1)->get();
            $custCategoryList = CustomerCategory::where('store_id', $store_id)->where('is_active', 1)->get();
            $productList = Product::where('store_id', $store_id)->where('is_active', 1)
                        ->with('unit', 'stockWarehouse', 'stockWarehouse.warehouse', 'composition', 'composition.productSupplier')->get();

            $paidTypeList = PaidType::where('store_id', $store_id)->where('is_active', 1)->get();
        }

        return view('mazuprocess.salesOrderTable', [
            'MenuID'            => $this->MenuID,
            'isEvent'           => $isEvent,
            'eventList'         => $eventList,
            'customerList'      => $customerList,
            'custCategoryList'  => $custCategoryList,
            'productList'       => $productList,
            'paidTypeList'      => $paidTypeList,
        ]);
    }

    public function justAddCustomer(Request $request){
        // if(!isAccess('create', $this->MenuID)){
        //     return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        // }
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
            $customer = Customer::create([
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

            return response()->json(['status' => 'Success', 'message' => 'Add customer success.', 'data' => $customer], 200);

        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function loadSO($start_date, $end_date){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $store_id = getStoreId();
        $soList = SalesOrder::where('is_active', 1)
                            ->whereBetween('so_date', [$start_date, $end_date])
                            ->where('store_id', $store_id)
                            ->where('so_type', $this->so_type)
                            ->with('customer', 'items', 'items.product', 'items.product.unit', 'paid', 'paid.paidType')
                            ->orderBy('created_at', 'desc')
                            ->get();

        return['data'=> $soList];
    }

    public function loadProduct(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $isEvent = isEvent();
        if($isEvent){
            $productList = Product::where('is_active', 1)
                ->with('category', 'unit', 'stockWarehouse', 'stockWarehouse.warehouse')
                ->orderBy('created_at', 'DESC')->get();
        }else{
            $productList = Product::where('store_id', getStoreId())->where('is_active', 1)
                ->with('category', 'unit', 'stockWarehouse', 'stockWarehouse.warehouse')
                ->orderBy('created_at', 'DESC')->get();
        }


        return['data'=> $productList];
    }

    function getProductLabel($product_label){
        $store_id = getStoreId();
        $isEvent = isEvent();
        if($isEvent){
            $data = LabelProduct::where('no_label', strtoupper($product_label))
                            ->where('is_print', 1)
                            ->where('is_checked_in', 0)
                            ->get()->first();
        }else{
            $data = LabelProduct::where('no_label', strtoupper($product_label))
                            ->where('is_print', 1)
                            ->where('is_checked_in', 0)
                            ->where('store_id', $store_id)
                            ->get()->first();
        }

        return['data'=> $data];
    }

    public function addSO(Request $request)
    {
        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        DB::beginTransaction();
        try {
            $arrsuccess = 0;
            $so_id = Uuid::uuid4()->toString();
            $so_number = "";
            $totalHpp = floatval(0);
            $decPaid = floatval(0);
            $decRemain = floatval(0);

            if($request->is_process){
                $so_number = $this->objNumberingForm->GENERATE_FORM_NUMBER($this->generateType);
                $decPaid = $request->dec_paid_fin;
                $decRemain = $request->dec_remain;
            }

            for ($i=0; $i<count($request->product_id); $i++ ){
                $productHpp = Product::where('product_id', $request->product_id[$i])->pluck('hpp')->first();
                $compositionList = ProductComposition::where('product_id', $request->product_id[$i])
                                        ->with('productSupplier')->get();

                $totalHpp += (floatval($productHpp) * floatval($request->qty_order_item[$i]));
                foreach ($compositionList as $ls) {
                    $amount_usage = ((floatval($ls->amount_usage) * floatval($request->qty_order_item[$i])));
                    $totalHpp += floatval($amount_usage) * floatval($ls->productSupplier->price);
                }
            }


            $so = SalesOrder::create([
                'so_id'                         => $so_id,
                'so_number'                     => $so_number,
                'so_date'                       => $request->so_date,
                'so_type'                       => $this->so_type,
                'customer_id'                   => $request->customer_id,
                'description'                   => $request->description,
                'total_hpp'                     => $totalHpp,
                'total_price'                   => $request->total_price,
                'percent_discount'              => $request->percent_discount,
                'total_price_after_discount'    => $request->total_price_after_discount,
                'ppn'                           => $request->ppn,
                'shipping_cost'                 => $request->shipping_cost,
                'grand_total'                   => (floatval($request->grand_total) - floatval($request->shipping_cost)),
                'grand_total_wshipping'         => $request->grand_total,
                'dec_paid'                      => $decPaid,
                'dec_remain'                    => $decRemain,
                'is_po_customer'                => 0,
                'is_process'                    => $request->is_process,
                'is_draft'                      => $request->is_draft,
                'is_void'                       => 0,
                'is_active'                     => 1,
                'store_id'                      => getStoreId(),
                'created_user'                  => Auth::User()->employee->employee_name,
            ]);

            if($so){
                // if($so->is_process){
                //     $storeList = Store::where('is_active', 1)->where('is_event', 0)->get();
                //     foreach($storeList as $store){
                //         $so_id_store = Uuid::uuid4()->toString();
                //         $hpp_store = floatval(0);
                //         $total_price_store = floatval(0);
                //         $total_price_after_discount_store = floatval(0);
                //         for ($i=0; $i<count($request->product_id); $i++ ){
                //             $product_store = Product::where('product_id', $request->product_id[$i])->get()->first();
                //             if($product_store->store_id === $store->store_id){
                //                 $hpp_store += $product_store->hpp;
                //                 $total_price_store += $request->total_price_item[$i];
                //                 $total_price_after_discount_store += $request->total_price_after_discount_item[$i];
                //             }

                //         }
                //     }
                // }
                for ($i=0; $i<count($request->product_id); $i++ ){
                    $hpp = floatval(0);
                    $productHppItem = Product::where('product_id', $request->product_id[$i])->pluck('hpp')->first();
                    $compositionListItem = ProductComposition::where('product_id', $request->product_id[$i])
                                            ->with('productSupplier')->get();

                    $hpp += floatval($productHppItem);
                    foreach ($compositionListItem as $ls) {
                        $amount_usage = ((floatval($ls->amount_usage) * floatval($request->qty_order_item[$i])));
                        $totalHpp += floatval($amount_usage) * floatval($ls->productSupplier->price);
                    }

                    $item = SalesOrderItem::create([
                        'so_id'                         => $so_id,
                        'product_id'                    => $request->product_id[$i],
                        'qty_order'                     => $request->qty_order_item[$i],
                        'hpp'                           => $hpp,
                        'price'                         => $request->price_item[$i],
                        'percent_discount'              => $request->percent_discount_item[$i],
                        'total_price'                   => $request->total_price_item[$i],
                        'total_price_after_discount'    => $request->total_price_after_discount_item[$i],
                        'description'                   => $request->description_item[$i],
                        'product_label_list'            => $request->product_label_list[$i],
                        'order_item'                    => $arrsuccess,
                    ]);

                    if($item){
                        if($request->is_process){
                            $warehouseProduct = Stock::where('product_id', $item->product_id)->pluck('warehouse_id')->first();
                            $this->objStock->minStock($item->product_id, $warehouseProduct, $item->qty_order, "Sales Order ".$so_number);

                            $compositionList = ProductComposition::where('product_id', $item->product_id)
                                            ->with('productSupplier.stockWarehouse')->get();

                            foreach ($compositionList as $ls) {
                                if(!$ls->productSupplier->is_service){
                                    $amount_usage = (floatval($ls->amount_usage) * floatval($item->qty_order));
                                    $this->objStock->minStockSupplier($ls->product_supplier_id, $ls->productSupplier->stockWarehouse->warehouse_id, $amount_usage, "Sales Order ".$so_number);
                                }
                            }
                        }

                        $arrsuccess++;
                    }
                }

                if($request->is_process){
                    $sales_order_paid_id = Uuid::uuid4()->toString();
                    $soPaid = SalesOrderPaid::create([
                        'sales_order_paid_id'           => $sales_order_paid_id,
                        'so_id'                         => $so->so_id,
                        'paid_type_id'                  => $request->paid_type_id,
                        'dec_paid'                      => $decPaid,
                        'dec_remain'                    => $decRemain,
                        'is_po_customer'                => 0,
                    ]);

                    if($soPaid){
                        $this->objGl->creditSalesOrder($soPaid);
                    }
                }
            }

            if ($so && $arrsuccess == count($request->product_id)){
                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'Add sales order success.'], 200);
            } else {
                DB::rollback();
                return response()->json(['status' => 'Error', 'message' => 'Add sales order failled, with a product error.' ], 202);
            }
        } catch (Exception  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 202);
        }
    }

    function updateSO(Request $request){
        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }
        // dd($request);
        DB::beginTransaction();
        try {
            $arrsuccess = 0;
            $so_number = "";
            $totalHpp = floatval(0);
            $decPaid = floatval(0);
            $decRemain = floatval(0);
            if($request->is_process){
                $so_number = $this->objNumberingForm->GENERATE_FORM_NUMBER($this->generateType);
                $decPaid = $request->dec_paid_fin;
                $decRemain = $request->dec_remain;
            }

            for ($i=0; $i<count($request->product_id); $i++ ){
                $productHpp = Product::where('product_id', $request->product_id[$i])->pluck('hpp')->first();
                $compositionList = ProductComposition::where('product_id', $request->product_id[$i])
                                        ->with('productSupplier')->get();

                $totalHpp += (floatval($productHpp) * floatval($request->qty_order_item[$i]));
                foreach ($compositionList as $ls) {
                    $amount_usage = ((floatval($ls->amount_usage) * floatval($request->qty_order_item[$i])));
                    $totalHpp += floatval($amount_usage) * floatval($ls->productSupplier->price);
                }
            }

            $so = SalesOrder::find($request->so_id);
            $so->update([
                'so_number'                     => $so_number,
                'so_date'                       => $request->so_date,
                'customer_id'                   => $request->customer_id,
                'description'                   => $request->description,
                'total_hpp'                     => $totalHpp,
                'total_price'                   => $request->total_price,
                'percent_discount'              => $request->percent_discount,
                'total_price_after_discount'    => $request->total_price_after_discount,
                'ppn'                           => $request->ppn,
                'shipping_cost'                 => $request->shipping_cost,
                'grand_total'                   => (floatval($request->grand_total) - floatval($request->shipping_cost)),
                'grand_total_wshipping'         => $request->grand_total,
                'dec_paid'                      => $decPaid,
                'dec_remain'                    => $decRemain,
                'is_process'                    => $request->is_process,
                'is_draft'                      => $request->is_draft,
                'updated_user'                  => Auth::User()->employee->employee_name,
            ]);

            if($so){
                $deletedRows = SalesOrderItem::where('so_id', $so->so_id)->get();
                foreach ($deletedRows as $ls) {
                    $ls->delete();
                }

                for ($i=0; $i<count($request->product_id); $i++ ){
                    $hpp = floatval(0);
                    $productHppItem = Product::where('product_id', $request->product_id[$i])->pluck('hpp')->first();
                    $compositionListItem = ProductComposition::where('product_id', $request->product_id[$i])
                                            ->with('productSupplier')->get();

                    $hpp += floatval($productHppItem);
                    foreach ($compositionListItem as $ls) {
                        $amount_usage = ((floatval($ls->amount_usage) * floatval($request->qty_order_item[$i])));
                        $totalHpp += floatval($amount_usage) * floatval($ls->productSupplier->price);
                    }

                    $item = SalesOrderItem::create([
                        'so_id'                         => $so->so_id,
                        'product_id'                    => $request->product_id[$i],
                        'qty_order'                     => $request->qty_order_item[$i],
                        'hpp'                           => $hpp,
                        'price'                         => $request->price_item[$i],
                        'percent_discount'              => $request->percent_discount_item[$i],
                        'total_price'                   => $request->total_price_item[$i],
                        'total_price_after_discount'    => $request->total_price_after_discount_item[$i],
                        'description'                   => $request->description_item[$i],
                        'product_label_list'            => $request->product_label_list[$i],
                        'order_item'                    => $arrsuccess,
                    ]);

                    if($item){
                        if($request->is_process){
                            $warehouseProduct = Stock::where('product_id', $item->product_id)->pluck('warehouse_id')->first();
                            $this->objStock->minStock($item->product_id, $warehouseProduct, $item->qty_order, "Sales Order ".$so_number);

                            $compositionList = ProductComposition::where('product_id', $item->product_id)
                                            ->with('productSupplier.stockWarehouse')->get();

                            foreach ($compositionList as $ls) {
                                if(!$ls->productSupplier->is_service){
                                    $amount_usage = (floatval($ls->amount_usage) * floatval($item->qty_order));
                                    $this->objStock->minStockSupplier($ls->product_supplier_id, $ls->productSupplier->stockWarehouse->warehouse_id, $amount_usage, "Sales Order ".$so_number);
                                }
                            }
                        }

                        $arrsuccess++;
                    }
                }

                if($request->is_process){
                    $sales_order_paid_id = Uuid::uuid4()->toString();
                    $soPaid = SalesOrderPaid::create([
                        'sales_order_paid_id'           => $sales_order_paid_id,
                        'so_id'                         => $so->so_id,
                        'paid_type_id'                  => $request->paid_type_id,
                        'dec_paid'                      => $decPaid,
                        'dec_remain'                    => $decRemain,
                        'is_po_customer'                => 0,
                    ]);

                    if($soPaid){
                        $this->objGl->creditSalesOrder($soPaid);
                    }
                }
            }

            if ($so && $arrsuccess == count($request->product_id)){
                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'Add sales order success.'], 200);
            } else {
                DB::rollback();
                return response()->json(['status' => 'Error', 'message' => 'Add sales order failled, with a product error.' ], 202);
            }
        } catch (Exception  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 202);
        }
    }

    function deleteSO($so_id){
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $so = SalesOrder::where('so_id', $so_id)
                ->with('paid', 'items', 'items.product', 'items.product.stockWarehouse', 'items.product.composition', 'items.product.composition.productSupplier', 'items.product.composition.productSupplier.stockWarehouse')->get()->first();
            if ($so){
                if($so->is_process){
                    foreach ($so->items as $ls) {
                        $this->objStock->plusStock($ls->product_id, $ls->product->stockWarehouse->warehouse_id, $ls->qty_order, "Cancel sales order ".$so->so_number);
                        foreach ($ls->product->composition as $item) {
                            if(!$item->productSupplier->is_service){
                                $amount_usage = (floatval($item->amount_usage) * floatval($ls->qty_order));
                                $this->objStock->plusStockSupplier($item->product_supplier_id, $item->productSupplier->stockWarehouse->warehouse_id, $amount_usage, "Cancel sales order ".$so->so_number);
                            }
                        }
                    }
                }
                $so->is_active = 0;
                $so->is_process = 0;
                $so->is_draft = 0;
                $so->is_void = 0;
                $so->update();

                foreach($so->paid as $paid){
                    $this->objGl->creditSalesOrderDelete($paid);
                }

                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'Delete sales order success.'], 200);
            } else {
                DB::rollBack();
                return response()->json(['status' => 'Info', 'message' => 'delete sales order failed.'], 200);
            }

        } catch (Exception  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 202);
        }
    }

    public function loadPayment($so_id){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $payementList = SalesOrderPaid::where('so_id', $so_id)
                            ->with('paidType')
                            ->orderBy('created_at', 'desc')
                            ->get();

        return['data'=> $payementList];
    }

    public function addPayment(Request $request){
        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }
        // dd($request);
        DB::beginTransaction();
        try {
            $decPaid = $request->dec_paid_fin_payment;
            $decPaidTotal = floatval($request->dec_paid_fin_payment) + floatval($request->total_paid_payment);
            $decRemain = $request->dec_remain_payment;

            $so = SalesOrder::find($request->so_id_payment);
            if($so){
                $so->update([
                    'dec_paid'                      => $decPaidTotal,
                    'dec_remain'                    => $decRemain,
                    'updated_user'                  => Auth::User()->employee->employee_name,
                ]);
                $sales_order_paid_id = Uuid::uuid4()->toString();
                $paid = SalesOrderPaid::create([
                    'sales_order_paid_id'           => $sales_order_paid_id,
                    'so_id'                         => $so->so_id,
                    'paid_type_id'                  => $request->paid_type_id_payment,
                    'dec_paid'                      => $decPaid,
                    'dec_remain'                    => $decRemain,
                    'is_po_customer'                => 0,
                ]);

                if($paid){
                    $this->objGl->creditSalesOrder($paid);
                }

                if ($so && $paid){
                    DB::commit();
                    return response()->json(['status' => 'Success', 'message' => 'Add sales order payment success.'], 200);
                } else {
                    DB::rollback();
                    return response()->json(['status' => 'Error', 'message' => 'Add sales order payment failled, with a product error.' ], 202);
                }
            }else{
                DB::rollback();
                return response()->json(['status' => 'Error', 'message' => 'Add sales order payment failled, sales order not found.' ], 202);
            }
        } catch (Exception  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 202);
        }
    }

    public function printSalesOrder($so_id){

        if(!isAccess('read', $this->MenuID)){
            return "You do not have access for this action";
        }

        $SO = SalesOrder::with('customer', 'items', 'items.product', 'items.product.unit')
                            ->where('so_id', $so_id)->first();

        if($SO){
            $data = ['data'  => $SO];
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('mazuprocess.print.salesOrder', $data);
            $pdf->setPaper('A4', 'potrait');
            return $pdf->stream('INVOICE_'.$SO->so_number.'.pdf');
        } else {
            return "Data not found";
        }

    }
    public function printSalesOrderStruk($so_id){

        if(!isAccess('read', $this->MenuID)){
            return "You do not have access for this action";
        }
        $height = 250;
        $item_height = 35;
        $SO = SalesOrder::with('customer', 'items', 'items.product', 'items.product.unit')
                            ->where('so_id', $so_id)->first();

        $haveItem = count($SO->items);
        $height += ($item_height * $haveItem);
        if($SO){
            $data = ['data'  => $SO];
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('mazuprocess.print.salesOrderStruk', $data);
            $pdf->setPaper([0, 0, 200, $height], 'potrait');
            return $pdf->stream('INVOICE_'.$SO->so_number.'.pdf');
        } else {
            return "Data not found";
        }

    }
}
