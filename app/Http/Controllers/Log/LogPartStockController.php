<?php

namespace App\Http\Controllers\Log;

use Illuminate\Http\Request;
use App\Models\Log\PartStock;
use App\Models\Part\PartPrice;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LogPartStockController extends Controller
{
    function addLogStockIN($part_supplier_id, $part_customer_id, $warehouse_id, $qty, $description){
        $this->justAddLog($part_supplier_id, $part_customer_id, $warehouse_id, $qty, 'IN', $description);
    }

    function addLogStockOUT($part_supplier_id, $part_customer_id, $warehouse_id, $qty, $description){
        $this->justAddLog($part_supplier_id, $part_customer_id, $warehouse_id, $qty, 'OUT', $description);
    }

    function deleteLogStock($part_supplier_id, $part_customer_id, $warehouse_id, $key_id){
        
        if ($part_supplier_id == null){
            PartStock::where('description', 'like', '%' . $key_id . '%')
                    ->where('part_customer_id', $part_customer_id )
                    ->where('warehouse_id', $warehouse_id )
                    ->delete();
        } else {
            PartStock::where('description', 'like', '%' . $key_id . '%')
                    ->where('part_supplier_id', $part_supplier_id )
                    ->where('warehouse_id', $warehouse_id )
                    ->delete();
                   
        }
        
    }

    function justAddLog($part_supplier_id, $part_customer_id, $warehouse_id, $qty, $type, $description){
        PartStock::create([
            'part_supplier_id'  => $part_supplier_id,
            'part_customer_id'  => $part_customer_id,
            'warehouse_id'      => $warehouse_id,
            'type'              => $type,
            'qty'               => $qty,
            'date_log'          => date("Y-m-d"),
            'description'       => $description,
            'created_user'      => Auth::User()->employee->employee_name,
        ]);
    }

    //LOG PART SUPPLIER
    function getLogPartStock($part_supplier_id){

        $partStockList = PartStock::where('part_supplier_id', $part_supplier_id)->with('part_supplier', 'warehouse')->get();
        return['data'=> $partStockList];
    }

    function getLogPartStockByPeriode($part_supplier_id, $month, $year){

        $date = date($year."-".$month."-1");
        $datefrom = date("Y-m-1", strtotime($date));
        $dateto = date("Y-m-t", strtotime($date));

        $partStockList = PartStock::where('part_supplier_id', $part_supplier_id)
                                    ->whereBetween('date_log', [$datefrom, $dateto])
                                    ->with('part_supplier', 'warehouse')->get();
        return['data'=> $partStockList];
    }

    //LOG PART CUSTOMER
    function getLogPartCustomerStock($part_customer_id){

        $partStockList = PartStock::where('part_customer_id', $part_customer_id)->with('part_customer', 'warehouse')->get();
        return['data'=> $partStockList];
    }

    function getLogPartCustomerStockByPeriode($part_customer_id, $month, $year){

        $date = date($year."-".$month."-1");
        $datefrom = date("Y-m-1", strtotime($date));
        $dateto = date("Y-m-t", strtotime($date));

        $partStockList = PartStock::where('part_customer_id', $part_customer_id)
                                    ->whereBetween('date_log', [$datefrom, $dateto])
                                    ->with('part_customer', 'warehouse')->get();
        return['data'=> $partStockList];
    }

}
