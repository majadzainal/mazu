<?php

namespace App\Http\Controllers\MazuProcess;

use Exception;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\MazuProcess\StockOpname;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\MazuProcess\StockOpnameItem;
use App\Models\MazuMaster\StockOpnameSchedule;
use App\Http\Controllers\MazuLog\LogStockController;
use App\Http\Controllers\MazuMaster\StockController;

class StockOpnameProductController extends Controller
{
    public $MenuID = '01502';
    public $objStock;
    public $objLogStock;
    public $objNumberingForm;

    public function __construct()
    {
        $this->objStock = new StockController();
        $this->objLogPartStock = new LogStockController();
    }

    public function listStockOpname(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }
        $store_id = getStoreId();
        $now = Carbon::now()->format('Y-m-d');
        $productOpnameList = StockOpname::where('opname_type_id', 1)
                    ->where('is_active', 1)
                    ->where('store_id', $store_id)
                    ->with('opname_item', 'opname_item.product', )
                    ->get();


        $scheduleList = StockOpnameSchedule::where('opname_date', '<=',  $now)
                    ->where('is_closed', 0)
                    ->where('is_active', 1)
                    ->where('store_id', $store_id)
                    ->get();

        return view('mazuprocess.stockOpnameProductTable', [
            'MenuID' => $this->MenuID,
            'productOpnameList' => $productOpnameList,
            'scheduleList' => $scheduleList,
        ]);

    }

    function loadData(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $store_id = getStoreId();
        $stock_opname = StockOpname::where('is_active', 1)
                            ->where('opname_type_id', 1)
                            ->with('opname_item', 'schedule',)
                            ->orderBy('created_at', 'desc')
                            ->where('store_id', $store_id)
                            ->get();

        return['data'=> $stock_opname];
    }

    function loadProduct(){
        $store_id = getStoreId();
        $stock = DB::table('tm_stock')
            ->leftJoin('tm_warehouse', 'tm_stock.warehouse_id', '=', 'tm_warehouse.warehouse_id')
            ->leftJoin('tm_product', 'tm_stock.product_id', '=', 'tm_product.product_id')
            ->leftJoin('tm_unit', 'tm_product.unit_id', '=', 'tm_unit.unit_id')
            ->where('tm_stock.store_id', $store_id)
            ->where('tm_product.is_active', 1)
            ->select('tm_stock.stock_id'
                    , 'tm_stock.product_id'
                    , 'tm_stock.warehouse_id'
                    , 'tm_stock.stock'
                    , 'tm_warehouse.warehouse_name'
                    , 'tm_product.product_code'
                    , 'tm_product.product_name'
                    , 'tm_product.unit_id'
                    , 'tm_unit.unit_name')
            ->orderBy('tm_product.product_name', 'DESC')
            ->get();

        return['data'=> $stock];
    }

    function loadProductEdit($stock_opname_id){
        $item = StockOpnameItem::where('stock_opname_id', $stock_opname_id)
                    ->with('product', 'product.unit', 'warehouse')
                    ->orderBy('order_item', 'ASC')
                    ->get();


        return['data'=> $item];
    }

    public function addOpnameProduct(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }

        // dd($request);

        DB::beginTransaction();
        try {
            $arrsuccess = 0;
            $store_id = getStoreId();
            $stock_opname_id = Uuid::uuid4()->toString();
            $stock_opname = StockOpname::create([
                'stock_opname_id'           => $stock_opname_id,
                'stock_opname_schedule_id'  => $request->stock_opname_schedule_id,
                'opname_type_id'            => 1,
                'stock_opname_date'         => $request->stock_opname_date,
                'description'              => $request->description,
                'created_user'              => Auth::User()->employee->employee_name,
                'is_active'                 => 1,
                'store_id'                 => $store_id,
            ]);

            if($stock_opname){
                for ($i=0; $i<count($request->product_id); $i++ ){
                    if(intval($request->stock_adjustment[$i]) != 0){
                        $item = StockOpnameItem::create([
                            'stock_opname_id'       => $stock_opname->stock_opname_id,
                            'product_id'            => $request->product_id[$i],
                            'warehouse_id'          => $request->warehouse_id[$i],
                            'stock'                 => $request->stock[$i],
                            'stock_adjustment'      => intval($request->stock[$i]) + intval($request->stock_adjustment[$i]),
                            'deviation'             => $request->stock_adjustment[$i],
                            'unit_id'               => $request->unit_id[$i],
                            'note'                  => $request->note[$i],
                            'order_item'            => $i,
                        ]);

                        if($item){
                            $this->objStock->plusStock($item->product_id, $item->warehouse_id, $item->deviation, "Stock Opname FG. #".$stock_opname->stock_opname_id);

                            $arrsuccess++;
                        }
                    }
                }

                if (count($request->product_id) == $arrsuccess ){
                    DB::commit();
                    return response()->json(['status' => 'Success', 'message' => 'Add stock opname Product success.'], 200);
                } else {
                    DB::rollback();
                    return response()->json(['status' => 'Error', 'message' => 'Add stock opname product failled, with a part error.' ], 202);
                }
            }

        } catch (Exception  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 202);
        }
    }


    function deleteOpname($stock_opname_id){
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }

        DB::beginTransaction();
        try {

            $opname = StockOpname::where('stock_opname_id', $stock_opname_id)
                    ->with('opname_item')->get()->first();

            if ($opname){
                $opname->is_active = 0;
                $opname->update();

                foreach ($opname->opname_item as $ls){
                    $item = StockOpnameItem::where('stock_opname_item_id', $ls->stock_opname_item_id)->get()->first();
                    if($item){
                        $this->objStock->minStock($item->product_id, $item->warehouse_id, $item->deviation, "delete stock opname ".$item->stock_opname_id);
                        //$this->objStock->minStockPartCustomer($item->part_customer_id, $item->warehouse_id, $item->deviation, "Cancel Stock Opname FG. (".$opname->stock_opname_number.")");
                    }
                }

                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'delete stock opaname finished goods.'], 200);
            } else {
                DB::rollback();
                return response()->json(['status' => 'Info', 'message' => 'delete stock opaname finished goods failed.'], 200);
            }

        } catch (Exception  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 202);
        }
    }
}
