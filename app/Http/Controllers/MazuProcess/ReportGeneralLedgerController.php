<?php

namespace App\Http\Controllers\MazuProcess;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Foreach_;
use App\Http\Controllers\Controller;
use App\Models\MazuMaster\EventSchedule;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\MazuProcess\GeneralLedger;

class ReportGeneralLedgerController extends Controller
{
    public $MenuID = '09207';
    public $so_type = 6;

    public function reportTable(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        $isEvent = isEvent();
        $eventList = EventSchedule::where('is_active', 1)->where('is_closed', 0)->get();

        return view('mazuprocess.reportGeneralLedgerTable', [
            'MenuID' => $this->MenuID,
            'isEvent' => $isEvent,
            'eventList' => $eventList,
        ]);

    }

    public function loadReport($start_date, $end_date){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $isEvent = isEvent();

        $store_id = getStoreId();
        $credit = GeneralLedger::whereDate('gl_date', '<', $start_date)
                    ->where('store_id', $store_id)
                    ->sum('credit');
        $debit = GeneralLedger::whereDate('gl_date', '<', $start_date)
                    ->where('store_id', $store_id)
                    ->sum('debit');
        $glList = GeneralLedger::whereBetween('gl_date', [$start_date, $end_date])
                            ->where('store_id', $store_id)
                            ->with('poCustomer', 'poSupplier', 'production', 'poMaterial', 'soPaid', 'soPaid.salesOrder', 'cashFlow')
                            ->orderBy('created_at', 'asc')
                            ->get();
        // dd($glList);
        $saldo = $isEvent ? 0 : floatVal($credit) - floatVal($debit);
        $dataList = [];
        foreach($glList as $item){
            $data['gl_date'] = $item->gl_date;
            $debit = $item->debit != null ? $item->debit : 0;
            $credit = $item->credit != null ? $item->credit : 0;
            $data['debit'] = $debit;
            $data['credit'] = $credit;
            if($item->debit !== null){
                $saldo -= floatval($debit);
            }

            if($item->credit !== null){
                $saldo += floatval($credit);
            }

            $data['saldo'] = $saldo;
            $transNumber = "";
            $transNumber = $item->poCustomer !== null ? $item->poCustomer->po_number : $transNumber;
            $transNumber = $item->poSupplier !== null ? $item->poSupplier->po_number : $transNumber;
            $transNumber = $item->production !== null ? $item->production->po_number : $transNumber;
            $transNumber = $item->poMaterial !== null ? $item->poMaterial->po_number : $transNumber;
            $transNumber = $item->soPaid !== null ? $item->soPaid->salesOrder->so_number : $transNumber;
            $transNumber = $item->cashFlow !== null ? $item->cashFlow->cash_flow_code."-".$item->cashFlow->cash_flow_name : $transNumber;
            $data['transaction_number'] = $transNumber;

            array_push($dataList, $data);
        }
        // dd($dataList);
        // $dataList = $glList->map(function($item) use($saldo){
        //     $data['gl_date'] = $item->gl_date;
        //     $debit = $item->debit != null ? $item->debit : 0;
        //     $credit = $item->credit != null ? $item->credit : 0;
        //     $data['debit'] = $debit;
        //     $data['credit'] = $credit;
        //     if($item->debit !== null){
        //         $saldo -= floatval($debit);
        //     }

        //     if($item->credit !== null){
        //         $saldo += floatval($credit);
        //     }

        //     $data['saldo'] = $saldo;
        //     $transNumber = "";
        //     $transNumber = $item->poCustomer !== null ? $item->poCustomer->po_number : $transNumber;
        //     $transNumber = $item->poSupplier !== null ? $item->poSupplier->po_number : $transNumber;
        //     $transNumber = $item->production !== null ? $item->production->po_number : $transNumber;
        //     $transNumber = $item->poMaterial !== null ? $item->poMaterial->po_number : $transNumber;
        //     $transNumber = $item->soPaid !== null ? $item->soPaid->salesOrder->so_number : $transNumber;
        //     $transNumber = $item->cashOut !== null ? $item->cashOut->cash_out_code."-".$item->cashOut->cash_out_name : $transNumber;
        //     $data['transaction_number'] = $transNumber;
        //     return $data;
        // });
        // dd($partList);
        return['data'=> $dataList];
    }
}
