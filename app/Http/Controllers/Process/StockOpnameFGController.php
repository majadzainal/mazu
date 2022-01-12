<?php

namespace App\Http\Controllers\Process;

use Throwable;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Models\Part\Stock;
use App\Models\Master\Plant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Process\StockOpname;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Process\OpnameSchedule;
use App\Models\Process\StockOpnameItem;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\Part\StockController;
use App\Http\Controllers\Log\LogPartStockController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Setting\NumberingFormController;

class StockOpnameFGController extends Controller
{
    public $MenuID = '1002';
    public $objStock;
    public $objLogPartStock;
    public $objNumberingForm;
    public $generateType = 'F_STOCK_OPNAME_FG';

    public function __construct()
    {
        $this->objStock = new StockController();
        $this->objLogPartStock = new LogPartStockController();
        $this->objNumberingForm = new NumberingFormController();
    }

    public function listStockOpname(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }
        $now = Carbon::now()->format('Y-m-d');
        $partCustOpnameList = StockOpname::where('opname_type_id', 1)
                    ->where('is_active', 1)
                    ->with('opname_item', 'opname_item.partCustomer', )
                    ->get();


        $scheduleList = OpnameSchedule::where('opname_date', '<=',  $now)
                    ->where('is_closed', 0)
                    ->where('is_active', 1)
                    ->with('plant')
                    ->get();

        $plantList = Plant::where('is_active', 1)
                    ->get();

        return view('process.stockOpnameFGTable', [
            'MenuID' => $this->MenuID,
            'partCustOpnameList' => $partCustOpnameList,
            'scheduleList' => $scheduleList,
            'plantList' => $plantList,
        ]);

    }

    function loadData(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $stock_opname = StockOpname::where('is_active', 1)
                            ->where('opname_type_id', 1)
                            ->with('opname_item', 'schedule', 'plant')
                            ->orderBy('stock_opname_date', 'desc')
                            ->get();

        return['data'=> $stock_opname];
    }

    function loadPart($plant_id){
        $stock = DB::table('tm_stock')
            ->leftJoin('tm_warehouse', 'tm_stock.warehouse_id', '=', 'tm_warehouse.warehouse_id')
            ->leftJoin('tm_plant', 'tm_warehouse.plant_id', '=', 'tm_plant.plant_id')
            ->leftJoin('tm_part_customer', 'tm_stock.part_customer_id', '=', 'tm_part_customer.part_customer_id')
            ->leftJoin('tm_unit', 'tm_part_customer.unit_id', '=', 'tm_unit.unit_id')
            ->where('tm_warehouse.plant_id', $plant_id)
            ->where('tm_part_customer.is_supplier', 0)
            ->where('tm_part_customer.is_active', 1)
            ->select('tm_stock.stock_id'
                    , 'tm_stock.part_customer_id'
                    , 'tm_stock.warehouse_id'
                    , 'tm_stock.stock'
                    , 'tm_warehouse.warehouse_name'
                    , 'tm_warehouse.plant_id'
                    , 'tm_part_customer.part_number'
                    , 'tm_part_customer.part_name'
                    , 'tm_part_customer.unit_id'
                    , 'tm_plant.plant_name'
                    , 'tm_unit.unit_name')
            ->orderBy('tm_part_customer.part_number', 'DESC')
            ->get();

        return['data'=> $stock];
    }

    function loadPartEdit($stock_opname_id){
        $item = StockOpnameItem::where('stock_opname_id', $stock_opname_id)
                    ->with('partCustomer', 'partCustomer.unit', 'warehouse')
                    ->orderBy('order_item', 'ASC')
                    ->get();


        return['data'=> $item];
    }

    public function addOpnameFG(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }

        // dd($request);

        DB::beginTransaction();
        try {
            $arrsuccess = 0;

            $stock_opname_id = Uuid::uuid4()->toString();
            $stock_opname_number = $this->objNumberingForm->GENERATE_FORM_NUMBER($this->generateType);
            $stock_opname = StockOpname::create([
                'stock_opname_id'           => $stock_opname_id,
                'stock_opname_number'       => $stock_opname_number,
                'opname_schedule_id'        => $request->opname_schedule_id,
                'plant_id'                  => $request->plant_id,
                'opname_type_id'            => 1,
                'stock_opname_date'         => $request->stock_opname_date,
                'description'              => $request->description,
                'created_user'              => Auth::User()->employee->employee_name,
                'is_active'                 => 1,
            ]);

            if($stock_opname){
                for ($i=0; $i<count($request->part_customer_id); $i++ ){
                    $item = StockOpnameItem::create([
                        'stock_opname_id'       => $stock_opname->stock_opname_id,
                        'part_customer_id'      => $request->part_customer_id[$i],
                        'warehouse_id'          => $request->warehouse_id[$i],
                        'stock'                 => $request->stock[$i],
                        'stock_adjustment'      => intval($request->stock[$i]) + intval($request->stock_adjustment[$i]),
                        'deviation'             => $request->stock_adjustment[$i],
                        'unit_id'               => $request->unit_id[$i],
                        'note'                  => $request->note[$i],
                        'order_item'            => $i,
                    ]);

                    if($item){
                        $this->objStock->plusStockPartCustomer($item->part_customer_id, $item->warehouse_id, $item->deviation, "Stock Opname FG. (".$stock_opname->stock_opname_number.")");

                        $arrsuccess++;
                    }
                }

                if (count($request->part_customer_id) == $arrsuccess ){
                    DB::commit();
                    return response()->json(['status' => 'Success', 'message' => 'Add stock opname Finished Goods success.'], 200);
                } else {
                    DB::rollback();
                    return response()->json(['status' => 'Error', 'message' => 'Add stock opname Finished Goods failled, with a part error.' ], 202);
                }
            }

        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
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
                        $this->objStock->minStockPartCustomer($item->part_customer_id, $item->warehouse_id, $item->deviation, "Cancel Stock Opname FG. (".$opname->stock_opname_number.")");
                    }
                }

                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'delete stock opaname finished goods.'], 200);
            } else {
                DB::rollback();
                return response()->json(['status' => 'Info', 'message' => 'delete stock opaname finished goods failed.'], 200);
            }

        } catch (Throwable $e){
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }

}
