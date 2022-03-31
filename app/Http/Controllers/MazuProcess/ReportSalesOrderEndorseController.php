<?php

namespace App\Http\Controllers\MazuProcess;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MazuProcess\SalesOrder;
use RealRashid\SweetAlert\Facades\Alert;

class ReportSalesOrderEndorseController extends Controller
{
    public $MenuID = '09203';
    public $so_type = 1;

    public function reportSOTable(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        return view('mazuprocess.reportSalesOrderEndorseTable', [
            'MenuID' => $this->MenuID,
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
                            ->with('endorse')
                            ->orderBy('created_at', 'desc')
                            ->get();

        return['data'=> $soList];
    }
}
