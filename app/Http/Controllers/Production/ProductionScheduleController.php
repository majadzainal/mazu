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

class ProductionScheduleController extends Controller
{
    public $MenuID = '0901';

    public function listProductionSchedule(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        $soList = SalesOrder::where('is_active', 1)->with('customer')->orderBy('year_periode', 'DESC')->orderBy('month_periode', 'DESC')->get();
        $dayOffList = DayOff::where('is_active', 1)->get();
        $plantList = Plant::where('is_active', 1)->get();

        return view('production.productionSchedule', [
            'MenuID' => $this->MenuID,
            'soList' => $soList,
            'dayOffList' => $dayOffList,
            'plantList' => $plantList
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

    public function createProductionSchedule($so, $item){

        $this->deleteProductionSchedule($so->sales_order_id, $item->part_customer_id);

        $date = date($so->year_periode."-".$so->month_periode."-1");
        $datefrom = date("Y-m-1", strtotime($date));
        $dateto = date("Y-m-t", strtotime($date));
        $dayOffList = DayOff::whereBetween('day_off', [$datefrom, $dateto])->get();
        $total_day = 0;

        for($i=1; $i<= date("t", strtotime($date)); $i++){
            $temp_date = date($so->year_periode."-".$so->month_periode."-".$i);
            $checkDayOff = $dayOffList->where('day_off', date("Y-m-d", strtotime($temp_date)))->count();
            if (date("w", strtotime($temp_date)) !== '0' && date("w", strtotime($temp_date)) !== '6') {
                if($checkDayOff == 0){
                    $total_day++ ;
                }
            }
        }

        $temp_qty = $item->qty;
        $temp_day = $total_day;

        for($i=1; $i<= date("t", strtotime($date)); $i++){
            $temp_date = date($so->year_periode."-".$so->month_periode."-".$i);
            $checkDayOff = $dayOffList->where('day_off', date("Y-m-d", strtotime($temp_date)))->count();
            if (date("w", strtotime($temp_date)) !== '0' && date("w", strtotime($temp_date)) !== '6') {
                if($checkDayOff == 0){
                    $schedule_date = date("Y-m-d", strtotime($temp_date));
                    $qty = ceil($temp_qty/$temp_day);

                    $ps = ProductionSchedule::create([
                        'sales_order_id'        => $so->sales_order_id,
                        'part_customer_id'      => $item->part_customer_id,
                        'schedule_date'         => $schedule_date,
                        'shift1'                => 1,
                        'shift2'                => 1,
                        'qty'                   => $qty,
                    ]);

                    if($ps){
                        $temp_day = $temp_day - 1;
                        $temp_qty = $temp_qty - $qty;
                    }
                }
            }
        }
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

    public function deleteProductionSchedule($soID, $partID){

        ProductionSchedule::where('sales_order_id', $soID)->where('part_customer_id', $partID)->delete();
    }

}
