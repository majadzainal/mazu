<?php

namespace App\Http\Controllers\MazuProcess;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MazuProcess\SalesOrder;
use App\Models\MazuMaster\EventSchedule;
use RealRashid\SweetAlert\Facades\Alert;

class ReportSalesOrderController extends Controller
{
    public $MenuID = '09201';
    public $so_type = 7;

    public function reportSOTable(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        $isEvent = isEvent();
        $eventList = EventSchedule::where('is_active', 1)->where('is_closed', 0)->get();
        return view('mazuprocess.reportSalesOrderTable', [
            'MenuID' => $this->MenuID,
            'isEvent' => $isEvent,
            'eventList' => $eventList,
        ]);

    }

    public function loadReportSO($start_date, $end_date){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $store_id = getStoreId();
        $soList = SalesOrder::where('is_active', 1)
                            ->whereBetween('so_date', [$start_date, $end_date])
                            ->where('store_id', $store_id)
                            ->where('is_draft', 0)
                            ->where('is_void', 0)
                            ->where('is_process', 1)
                            ->where('so_type', $this->so_type)
                            ->with('customer')
                            ->orderBy('created_at', 'desc')
                            ->get();

        return['data'=> $soList];
    }
}
