<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Part\PartSupplier;
use App\Models\Part\PartCustomer;
use App\Models\Process\Budgeting;
use App\Models\Master\Plant;
use App\Models\production\DailyReport;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public $MenuID = '01';

    public function index(){
        $now = Carbon::now();
        $month = $now->month;
        $year = $now->year;
        $qtyBudgeting = 0;
        $qtySo = 0;

        $budgeting = Budgeting::groupBy('year_periode', 'month_periode')
                    ->selectRaw('sum(qty) as qty')
                    ->selectRaw('sum(total_price) as total_price')
                    ->where('year_periode', $year)
                    ->where('month_periode', $month)
                    ->get()->first();
        $so = DB::table('tp_so_item')
                ->join('tp_sales_order', 'tp_so_item.sales_order_id', '=', 'tp_sales_order.sales_order_id')
                ->selectRaw('sum(tp_so_item.qty) as qty')
                ->where('year_periode', $year)
                ->where('month_periode', $month)
                ->get()->first();

        $plantList = Plant::where('is_active', 1)->get();

        $qtyBudgeting = $budgeting !== null ? $budgeting->qty : $qtyBudgeting;
        $qtySo = $so !== null ? $so->qty : $qtySo;

        return view('dashboard', [
            'MenuID'           => $this->MenuID,
            'qtyBudgeting'     => $qtyBudgeting,
            'qtySo'            => $qtySo,
            'plantList'        => $plantList
        ]);
    }

    public function loadPartSupplier(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $partList = PartSupplier::where('is_active', 1)
                                ->whereRaw('stock < minimum_stock')
                                ->with('supplier', 'divisi')->get();
        return['data'=> $partList];
    }

    function getMachineProcess($plant){

        $partCustomer = PartCustomer::where('is_active', 1)
                                    ->when($plant > 0, function ($q) use ($plant) {
                                        return $q->where('plant_id', $plant);
                                    })
                                    ->pluck('part_customer_id');

        
        $getDailyReport = DB::table('tp_daily_report as dr')
                    ->groupBy('pm.code', 'dr.report_date')
                    ->select('pm.code', 'dr.report_date', DB::raw('SUM(bpi.cycle_time * dri.total) as total_ct'))
                    ->leftJoin('tp_daily_report_item as dri', 'dr.report_id', '=', 'dri.report_id')
                    ->leftJoin('tm_bill_process as bp', 'dri.part_customer_id', '=', 'bp.part_customer_id')
                    ->leftJoin('tm_bill_process_item as bpi', 'bp.bill_process_id', '=', 'bpi.bill_process_id')
                    ->leftJoin('tm_process_machine as pm', 'bpi.mc', '=', 'pm.pmachine_id')
                    ->whereIn('dri.part_customer_id', $partCustomer)
                    //->whereBetween('report_date', [date('Y-m-d'), date('Y-m-d',(strtotime ( '-14 day' , strtotime (date('Y-m-d')) ) ))])
                    ->orderBy('dr.report_date', 'ASC')
                    ->get();

        return['data'=> $getDailyReport];
        
    }

}
