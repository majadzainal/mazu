<?php

namespace App\Http\Controllers\Production;

use Throwable;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Production\ProductionSchedule;
use App\Models\Process\SalesOrder;
use App\Models\Master\DayOff;
use App\Models\Master\Plant;
use App\Models\Part\PartCustomer;

class GenerateScheduleController extends Controller
{
    public $MenuID = '0907';

    public function initGenerateSchedule(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        $soList = SalesOrder::where('is_active', 1)->with('customer', 'so_items', 'so_items.partCustomer')->orderBy('year_periode', 'DESC')->orderBy('month_periode', 'DESC')->get();
        $dayOffList = DayOff::where('is_active', 1)->get();
        return view('production.generateSchedule', [
            'MenuID' => $this->MenuID,
            'soList' => $soList,
            'dayOffList' => $dayOffList,
        ]);
    }

    public function getProductionSchedule($plant, $month, $year){

        $date = date($year."-".$month."-1");
        $datefrom = date("Y-m-1", strtotime($date));
        $dateto = date("Y-m-t", strtotime($date));

        $part = PartCustomer::where('plant_id', $plant)->pluck('part_customer_id');
        $partList = ProductionSchedule::groupBy('part_customer_id')
                                        ->with('part_customer')
                                        ->select('part_customer_id')
                                        ->whereBetween('schedule_date', [$datefrom, $dateto])
                                        ->whereIn('part_customer_id', $part)->get();

        $scheduleList = ProductionSchedule::whereBetween('schedule_date', [$datefrom, $dateto])
                                        ->whereIn('part_customer_id', $part)->get();

        return['data'=> ['partList'=> $partList,
                         'scheduleList'=> $scheduleList
                        ]
                ];

    }

    public function createGenerateSchedule(Request $request){
        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        DB::beginTransaction();
        try {
            $arrsuccess = 0;
            $scheduleList = json_decode($request->scheduleList, TRUE);
            $this->deleteGenerateSchedule($scheduleList[0]['sales_order_id']);
            foreach($scheduleList as $ls){
                $ps = ProductionSchedule::create([
                    'sales_order_id'        => $ls['sales_order_id'],
                    'part_customer_id'      => $ls['part_customer_id'],
                    'schedule_date'         => $ls['schedule_date'],
                    'shift1'                => $ls['shift1'],
                    'shift2'                => $ls['shift2'],
                    'qty'                   => $ls['qty']
                ]);

                if($ps)
                    $arrsuccess++;
            }

            if ($arrsuccess == count($scheduleList)){
                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'create generate schedule production success.'], 200);
            } else {
                DB::rollback();
                return response()->json(['status' => 'Error', 'message' => 'some create generate schedule production failed.' ], 202);
            }
        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }

    }

    public function deleteGenerateSchedule($soID){

        ProductionSchedule::where('sales_order_id', $soID)->delete();
    }

    function updateProductionSchedule(Request $request){
        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        DB::beginTransaction();
        try {

            $sumQty = ProductionSchedule::where('sales_order_id', $request->idSO)
                                            ->where('part_customer_id', $request->idPart)
                                            ->sum('qty');

            if($request->qty > $sumQty){
                return response()->json(['status' => 'Error', 'message' => 'Qty over limit '.$sumQty ], 202);
            }

            $productionSchedule = ProductionSchedule::find($request->idSchedule);

            if($productionSchedule){
                $productionSchedule->update([
                    'shift1'    => $request->shift1,
                    'shift2'    => $request->shift2,
                    'qty'       => $request->qty
                ]);
            }

            if ($productionSchedule){
                $this->updateProductionScheduleAfterEdit($sumQty, $request);

                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'Update Production Schedule success.'], 200);
            } else {
                DB::rollback();
                return response()->json(['status' => 'Error', 'message' => 'Update Production Schedule failled.' ], 202);
            }
        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }

    function updateProductionScheduleAfterEdit($sumQyt, $request){

        $sumQtyBefore = ProductionSchedule::where('sales_order_id', $request->idSO)
                                            ->where('part_customer_id', $request->idPart)
                                            ->whereDate('schedule_date', '<=', $request->schedule_date)
                                            ->sum('qty');

        $scheduleAfter = ProductionSchedule::where('sales_order_id', $request->idSO)
                                        ->where('part_customer_id', $request->idPart)
                                        ->whereDate('schedule_date', '>', $request->schedule_date)
                                        ->orderBy('schedule_date', 'ASC')
                                        ->get();

        $temp_qty = $sumQyt - $sumQtyBefore;
        $temp_day = $scheduleAfter->count();

        foreach($scheduleAfter as $ls){
            $qty = ceil($temp_qty/$temp_day);

            $productionSchedule = ProductionSchedule::find($ls->schedule_id);
            if($productionSchedule){
                $productionSchedule->update([
                    'qty'       => $qty
                ]);
            }

            if($productionSchedule){
                $temp_day = $temp_day - 1;
                $temp_qty = $temp_qty - $qty;
            }
        }

    }

    

}
