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
use App\Models\Master\Plant;
use App\Models\Part\PartCustomer;
use App\Models\Production\ProductionSchedule;
use App\Models\Production\RequestRawmat;
use App\Models\Production\RequestRawmatItem;
use PDF;
use Excel;

class ProductionReportController extends Controller
{
    public $MenuID = '0905';

    public function filterProductionReport(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        $plantList = Plant::where('is_active', 1)->get();
        $partCustomerList = PartCustomer::where('is_active', 1)->with('customer')->get();

        return view('production.productionReport', [
            'MenuID' => $this->MenuID,
            'partCustomerList' => $partCustomerList,
            'plantList' => $plantList
        ]);
    }

    function getProductionReport($plant, $datefrom, $dateto){

        if(!isAccess('read', $this->MenuID)){
            return "You do not have access for this action";
        }

        $arrRequest = RequestRawmat::whereBetween('request_date', [$datefrom, $dateto])
                    ->where('is_active', 1)
                    ->when($plant != 0, function($query) use ($plant){
                        return $query->where('plant_id', $plant);
                    })->get();


        $rawMatList = RequestRawmatItem::whereIn('request_id', $arrRequest->pluck('request_id'))
                        ->with('part_supplier', 'part_customer', 'warehouse', 'units')->get();

        $partCustomer = PartCustomer::whereIn('plant_id', $arrRequest->pluck('plant_id'))->pluck('part_customer_id');
        $partProductionList = ProductionSchedule::whereIn('schedule_date', $arrRequest->pluck('request_date'))
                            ->whereIn('part_customer_id', $partCustomer)
                            ->with('part_customer')
                            ->get();

        return['data'=> ['rawMatList'=> $rawMatList,
            'partProductionList' => $partProductionList
            ]
        ];

    }

    public function printProductionReport($plant, $datefrom, $dateto){

        if(!isAccess('read', $this->MenuID)){
            return "You do not have access for this action";
        }

        $report = $this->getProductionReport($plant, $datefrom, $dateto);
        $plants = Plant::where('plant_id', $plant)->get();

        $datareport = ['plant'  => $plants,
                 'datefrom' => $datefrom,
                 'dateto' => $dateto,
                 'report' => $report
                ];

        $pdf = PDF::loadView('production.printProductionReport', $datareport);
        $pdf->setPaper('A4', 'potrait');
        return $pdf->stream('Production_Report_'.date('Ymdhis').'.pdf');

    }

    public function printExcelProductionReport($plant, $datefrom, $dateto){

        if(!isAccess('read', $this->MenuID)){
            return "You do not have access for this action";
        }

        $report = $this->getProductionReport($plant, $datefrom, $dateto);
        $plants = Plant::where('plant_id', $plant)->get();

        Excel::create('New File', function($excel){
           $excel->sheet('First sheet', function($sheet){
                $sheet->loadView('excel.exp1');
           });
        })->export('xls');

    }


}
