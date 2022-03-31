<?php

namespace App\Http\Controllers\MazuProcess;

use Throwable;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Models\MazuMaster\Stock;
use App\Models\MazuMaster\Outlet;
use App\Models\MazuMaster\Product;
use Illuminate\Support\Facades\DB;
use App\Models\MazuMaster\PaidType;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\MazuProcess\SalesOrder;
use App\Models\MazuMaster\LabelProduct;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\MazuProcess\SalesOrderItem;
use App\Models\MazuProcess\SalesOrderPaid;
use App\Models\MazuMaster\ProductComposition;
use App\Http\Controllers\MazuMaster\StockController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Setting\NumberingFormController;
use App\Http\Controllers\MazuProcess\GeneralLedgerController;

class SalesOrderOutletController extends Controller
{
    public $MenuID = '00304';
    public $objStock;
    public $objNumberingForm;
    public $generateType = 'F_SALES_ORDER';
    public $so_type = 4;
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
        $outletList = Outlet::where('store_id', $store_id)->where('is_active', 1)->get();
        $productList = Product::where('store_id', $store_id)->where('is_active', 1)
                    ->with('unit', 'stockWarehouse', 'stockWarehouse.warehouse', 'composition', 'composition.productSupplier')->get();
        $paidTypeList = PaidType::where('store_id', $store_id)->where('is_active', 1)->get();

        return view('mazuprocess.salesOrderOutletTable', [
            'MenuID'            => $this->MenuID,
            'outletList'      => $outletList,
            'productList'       => $productList,
            'paidTypeList'      => $paidTypeList,
        ]);
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
                            ->with('outlet', 'items', 'items.product', 'items.product.unit', 'paid', 'paid.paidType')
                            ->orderBy('created_at', 'desc')
                            ->get();

        return['data'=> $soList];
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

    function getProductLabel($product_label){
        $data = LabelProduct::where('no_label', strtoupper($product_label))
                        ->where('is_print', 1)
                        ->where('is_checked_in', 0)
                        ->get()->first();

        return['data'=> $data];
    }

    public function addSO(Request $request)
    {
        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

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

                $totalHpp += floatval($productHpp);
                foreach ($compositionList as $ls) {
                    $amount_usage = ((floatval($ls->amount_usage) * floatval($request->qty_order_item[$i])));
                    $totalHpp += floatval($amount_usage) * floatval($ls->productSupplier->price);
                }
            }

            // dd($request);
            $so = SalesOrder::create([
                'so_id'                => $so_id,
                'so_number'                     => $so_number,
                'so_date'                       => $request->so_date,
                'so_type'                       => $this->so_type,
                'outlet_id'                     => $request->outlet_id,
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
                            $this->objStock->minStock($item->product_id, $warehouseProduct, $item->qty_order, "Sales Order  outlet ".$so_number);

                            $compositionList = ProductComposition::where('product_id', $item->product_id)
                                            ->with('productSupplier.stockWarehouse')->get();

                            foreach ($compositionList as $ls) {
                                if(!$ls->productSupplier->is_service){
                                    $amount_usage = (floatval($ls->amount_usage) * floatval($item->qty_order));
                                    $this->objStock->minStockSupplier($ls->product_supplier_id, $ls->productSupplier->stockWarehouse->warehouse_id, $amount_usage, "Sales Order  outlet ".$so_number);
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
                return response()->json(['status' => 'Success', 'message' => 'Add sales order  outlet  success.'], 200);
            } else {
                DB::rollback();
                return response()->json(['status' => 'Error', 'message' => 'Add sales order  outlet  failled, with a product error.' ], 202);
            }
        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }

    function updateSO(Request $request){
        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }
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

                $totalHpp += floatval($productHpp);
                foreach ($compositionList as $ls) {
                    $amount_usage = ((floatval($ls->amount_usage) * floatval($request->qty_order_item[$i])));
                    $totalHpp += floatval($amount_usage) * floatval($ls->productSupplier->price);
                }
            }

            $so = SalesOrder::find($request->so_id);
            $so->update([
                'so_number'                     => $so_number,
                'so_date'                       => $request->so_date,
                'outlet_id'                     => $request->outlet_id,
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
                            $this->objStock->minStock($item->product_id, $warehouseProduct, $item->qty_order, "Sales Order  outlet ".$so_number);

                            $compositionList = ProductComposition::where('product_id', $item->product_id)
                                            ->with('productSupplier.stockWarehouse')->get();

                            foreach ($compositionList as $ls) {
                                if(!$ls->productSupplier->is_service){
                                    $amount_usage = (floatval($ls->amount_usage) * floatval($item->qty_order));
                                    $this->objStock->minStockSupplier($ls->product_supplier_id, $ls->productSupplier->stockWarehouse->warehouse_id, $amount_usage, "Sales Order  outlet  ".$so_number);
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
                return response()->json(['status' => 'Success', 'message' => 'Add sales order  outlet  success.'], 200);
            } else {
                DB::rollback();
                return response()->json(['status' => 'Error', 'message' => 'Add sales order  outlet  failled, with a product error.' ], 202);
            }
        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }

    function deleteSO($so_id){
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            $so = SalesOrder::where('so_id', $so_id)
                ->with('paid', 'items', 'items.product', 'items.product.stockWarehouse', 'items.product.composition', 'items.product.composition.productSupplier', 'items.product.composition.productSupplier.stockWarehouse')->get()->first();
            if ($so){
                if($so->is_process){
                    foreach ($so->items as $ls) {
                        $this->objStock->plusStock($ls->product_id, $ls->product->stockWarehouse->warehouse_id, $ls->qty_order, "Cancel sales order outlet ".$so->so_number);
                        foreach ($ls->product->composition as $item) {
                            if(!$item->productSupplier->is_service){
                                $amount_usage = (floatval($item->amount_usage) * floatval($ls->qty_order));
                                $this->objStock->plusStockSupplier($item->product_supplier_id, $item->productSupplier->stockWarehouse->warehouse_id, $amount_usage, "Cancel sales order outlet ".$so->so_number);
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
                return response()->json(['status' => 'Success', 'message' => 'Delete sales order outlet success.'], 200);
            } else {
                DB::rollBack();
                return response()->json(['status' => 'Info', 'message' => 'delete sales order outlet failed.'], 200);
            }

        } catch (Throwable $e){
            DB::rollBack();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
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
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }
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
        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }

    public function printSalesOrder($so_id){

        if(!isAccess('read', $this->MenuID)){
            return "You do not have access for this action";
        }

        $SO = SalesOrder::with('outlet', 'items', 'items.product', 'items.product.unit')
                            ->where('so_id', $so_id)->first();

        // dd($SO);
        if($SO){
            $data = ['data'  => $SO];
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('mazuprocess.print.salesOrderOutlet', $data);
            $pdf->setPaper('A4', 'potrait');
            return $pdf->stream('INVOICE_'.$SO->so_number.'.pdf');
        } else {
            return "Data not found";
        }

    }
}
