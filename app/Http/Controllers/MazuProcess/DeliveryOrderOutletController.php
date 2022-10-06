<?php

namespace App\Http\Controllers\MazuProcess;

use Throwable;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Models\MazuMaster\Outlet;
use App\Models\MazuMaster\Product;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MazuMaster\StockController;
use App\Http\Controllers\MazuMaster\StockOutletController;
use Illuminate\Support\Facades\Auth;
use App\Models\MazuMaster\LabelProduct;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\MazuProcess\DeliveryOrderOutlet;
use App\Models\MazuProcess\DeliveryOrderOutletItem;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Setting\NumberingFormController;
use App\Models\MazuMaster\ProductComposition;
use App\Models\MazuMaster\Stock;

class DeliveryOrderOutletController extends Controller
{
    public $MenuID = '00303';
    public $objNumberingForm;
    public $objStockOutlet;
    public $objStock;
    public $generateType = 'F_DELIVERY_ORDER_OUTLET';

    public function __construct()
    {
        $this->objStock = new StockController();
        $this->objStockOutlet = new StockOutletController();
        $this->objNumberingForm = new NumberingFormController();
    }

    public function listDOOutlet(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        $store_id = getStoreId();
        $outletList = Outlet::where('store_id', $store_id)->where('is_active', 1)->get();
        $productList = Product::where('store_id', getStoreId())->where('is_active', 1)
                    ->with('category', 'unit', 'stockWarehouse', 'stockWarehouse.warehouse')
                    ->orderBy('created_at', 'DESC')->get();

        return view('mazuprocess.doOutletTable', [
            'MenuID'            => $this->MenuID,
            'outletList'        => $outletList,
            'productList'        => $productList,
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

    public function loadDOOutlet($start_date, $end_date){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $store_id = getStoreId();
        $doOutletList = DeliveryOrderOutlet::where('is_active', 1)
                                        ->whereBetween('do_date', [$start_date, $end_date])
                                        ->where('store_id', $store_id)
                                        ->with('outlet', 'items', 'items.product', 'items.product.unit')
                                        ->orderBy('do_date', 'desc')
                                        ->get();

        return['data'=> $doOutletList];
    }

    public function addDOOutlet(Request $request)
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
            $do_outlet_id = Uuid::uuid4()->toString();
            $do_number = "";
            if($request->is_process){
                $do_number = $this->objNumberingForm->GENERATE_FORM_NUMBER($this->generateType);
            }


            $do = DeliveryOrderOutlet::create([
                'do_outlet_id'                => $do_outlet_id,
                'do_number'                     => $do_number,
                'do_date'                       => $request->do_date,
                'outlet_id'                      => $request->outlet_id,
                'description'                   => $request->description,
                'is_process'                    => $request->is_process,
                'is_draft'                      => $request->is_draft,
                'is_void'                       => 0,
                'is_active'                     => 1,
                'store_id'                      => getStoreId(),
                'created_user'                  => Auth::User()->employee->employee_name,
            ]);

            if($do){
                $warehouse_id = Outlet::where('outlet_id', $do->outlet_id)
                            ->pluck('warehouse_id')->first();

                for ($i=0; $i<count($request->product_id); $i++ ){
                    $item = DeliveryOrderOutletItem::create([
                        'do_outlet_id'                  => $do_outlet_id,
                        'product_id'                    => $request->product_id[$i],
                        'qty'                           => $request->qty[$i],
                        'warehouse_id'                  => $warehouse_id,
                        'description'                   => $request->description_item[$i],
                        'product_label_list'            => $request->product_label_list[$i],
                        'order_item'                    => $arrsuccess,
                    ]);

                    if($item){
                        if($request->is_process){
                            $warehouseProduct = Stock::where('product_id', $item->product_id)->pluck('warehouse_id')->first();
                            $this->objStock->minStock($item->product_id, $warehouseProduct, $item->qty, "Delivery Order Outlet ".$do_number);
                            $this->objStockOutlet->plusStock($do->outlet_id, $item->product_id, $warehouse_id, $item->qty, "Delivery Order Outlet ".$do_number, $do->do_outlet_id);

                            $compositionList = ProductComposition::where('product_id', $item->product_id)
                                            ->with('productSupplier.stockWarehouse')->get();

                            foreach ($compositionList as $ls) {
                                if(!$ls->productSupplier->is_service){
                                    $amount_usage = (floatval($ls->amount_usage) * floatval($item->qty));
                                    $this->objStock->minStockSupplier($ls->product_supplier_id, $ls->productSupplier->stockWarehouse->warehouse_id, $amount_usage, "Delivery Order Outlet ".$do_number);
                                    $this->objStockOutlet->plusStockSupplier($do->outlet_id, $ls->product_supplier_id, $warehouse_id, $amount_usage, "Delivery Order Outlet ".$do_number, $do->do_outlet_id);
                                }
                            }
                        }

                        $arrsuccess++;
                    }
                }
            }

            if ($do && $arrsuccess == count($request->product_id)){
                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'Add delivery order outlet success.'], 200);
            } else {
                DB::rollback();
                return response()->json(['status' => 'Error', 'message' => 'Add delivery order outlet failled, with a part error.' ], 202);
            }
        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }
    public function updateDOOutlet(Request $request)
    {
        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        DB::beginTransaction();
        try {
            $do = DeliveryOrderOutlet::find($request->do_outlet_id);
            if(!$do->is_process){
                $arrsuccess = 0;
                $do_number = "";
                if($request->is_process){
                    $do_number = $this->objNumberingForm->GENERATE_FORM_NUMBER($this->generateType);
                }
                $do->update([
                    'do_number'                     => $do_number,
                    'do_date'                       => $request->do_date,
                    'outlet_id'                     => $request->outlet_id,
                    'description'                   => $request->description,
                    'is_process'                    => $request->is_process,
                    'is_draft'                      => $request->is_draft,
                    'is_void'                       => 0,
                    'is_active'                     => 1,
                    'updated_user'                  => Auth::User()->employee->employee_name,
                ]);

                if($do){
                    $deletedRows = DeliveryOrderOutletItem::where('do_outlet_id', $do->do_outlet_id)->get();
                    foreach ($deletedRows as $ls) {
                        $ls->delete();
                    }

                    $warehouse_id = Outlet::where('outlet_id', $do->outlet_id)
                                ->pluck('warehouse_id')->first();
                    for ($i=0; $i<count($request->product_id); $i++ ){
                        $item = DeliveryOrderOutletItem::create([
                            'do_outlet_id'                  => $do->do_outlet_id,
                            'product_id'                    => $request->product_id[$i],
                            'qty'                           => $request->qty[$i],
                            'warehouse_id'                  => $warehouse_id,
                            'description'                   => $request->description_item[$i],
                            'product_label_list'            => $request->product_label_list[$i],
                            'order_item'                    => $arrsuccess,
                        ]);

                        if($item){
                            if($request->is_process){
                                $warehouseProduct = Stock::where('product_id', $item->product_id)->pluck('warehouse_id')->first();
                                $this->objStock->minStock($item->product_id, $warehouseProduct, $item->qty, "Delivery Order Outlet ".$do_number);
                                $this->objStockOutlet->plusStock($do->outlet_id, $item->product_id, $warehouse_id, $item->qty, "Delivery Order Outlet ".$do_number, $do->do_outlet_id);

                                $compositionList = ProductComposition::where('product_id', $item->product_id)
                                                ->with('productSupplier.stockWarehouse')->get();

                                foreach ($compositionList as $ls) {
                                    if(!$ls->productSupplier->is_service){
                                        $amount_usage = (floatval($ls->amount_usage) * floatval($item->qty));
                                        $this->objStock->minStockSupplier($ls->product_supplier_id, $ls->productSupplier->stockWarehouse->warehouse_id, $amount_usage, "Delivery Order Outlet ".$do_number);
                                        $this->objStockOutlet->plusStockSupplier($do->outlet_id, $ls->product_supplier_id, $warehouse_id, $amount_usage, "Delivery Order Outlet ".$do_number, $do->do_outlet_id);
                                    }
                                }
                            }
                            $arrsuccess++;
                        }
                    }
                }

                if ($do && $arrsuccess == count($request->product_id)){
                    DB::commit();
                    return response()->json(['status' => 'Success', 'message' => 'Update delivery order outlet success.'], 200);
                } else {
                    DB::rollback();
                    return response()->json(['status' => 'Error', 'message' => 'Update delivery order outlet failled, with a part error.' ], 202);
                }
            }else{
                return response()->json(['status' => 'Error', 'message' => 'Update delivery order outlet failled, because this DO already processed or canceled.' ], 202);
            }
        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }
    function deleteDOOutlet($do_outlet_id){
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $do = DeliveryOrderOutlet::where('do_outlet_id', $do_outlet_id)->with('items')->get()->first();
            if ($do){
                // dd($do);
                if($do->is_process){
                    foreach ($do->items as $ls) {
                        $warehouseProduct = Stock::where('product_id', $ls->product_id)->pluck('warehouse_id')->first();

                        $this->objStock->plusStock($ls->product_id, $warehouseProduct, $ls->qty, "Cancel delivery Order Outlet ".$do->do_number);

                        $this->objStockOutlet->deleteStock($do->outlet_id, $ls->product_id, $ls->warehouse_id, $ls->qty, $do->do_outlet_id);

                        $compositionList = ProductComposition::where('product_id', $ls->product_id)
                                            ->with('productSupplier.stockWarehouse')->get();

                        foreach ($compositionList as $item) {
                            if(!$item->productSupplier->is_service){
                                $amount_usage = (floatval($item->amount_usage) * floatval($ls->qty));
                                $this->objStock->plusStockSupplier($item->product_supplier_id, $item->productSupplier->stockWarehouse->warehouse_id, $amount_usage, "Cancel delivery Order Outlet ".$do->do_number);
                                $this->objStockOutlet->deleteStockSupplier($do->outlet_id, $item->product_supplier_id, $ls->warehouse_id, $amount_usage, $do->do_outlet_id);
                            }
                        }
                    }
                }
                $do->is_active = 0;
                $do->is_draft = 0;
                $do->is_void = 1;
                $do->update();

                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'Delete delivery order outlet success.'], 200);
            } else {
                DB::rollBack();
                return response()->json(['status' => 'Info', 'message' => 'delete delivery order outlet failed.'], 200);
            }

        } catch (Throwable $e){
            DB::rollBack();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }

    function getProductLabel($product_label){
        $data = LabelProduct::where('no_label', strtoupper($product_label))
                        ->where('is_print', 1)
                        ->where('is_checked_in', 0)
                        ->get()->first();

        return['data'=> $data];
    }
}
