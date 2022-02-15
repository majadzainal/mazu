<?php

namespace App\Http\Controllers\MazuLog;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\MazuLog\LogStockExclusiveReseller;

class LogStockExcResellerController extends Controller
{
    function addLogStockIN($exc_reseller_id, $product_id, $warehouse_id, $qty, $description, $key_id){
        $this->justAddLog($exc_reseller_id, $product_id, $warehouse_id, $qty, 'IN', $description, $key_id);
    }

    function addLogStockOUT($exc_reseller_id, $product_id, $warehouse_id, $qty, $description, $key_id){
        $this->justAddLog($exc_reseller_id, $product_id, $warehouse_id, $qty, 'OUT', $description, $key_id);
    }

    function deleteLogStock($exc_reseller_id, $product_id, $warehouse_id, $key_id){

        LogStockExclusiveReseller::where('key_id', $key_id)
                    ->where('exc_reseller_id', $exc_reseller_id )
                    ->where('product_id', $product_id )
                    ->where('warehouse_id', $warehouse_id )
                    ->delete();
    }

    function justAddLog($exc_reseller_id, $product_id, $warehouse_id, $qty, $type, $description, $key_id){
        LogStockExclusiveReseller::create([
            'product_id'        => $product_id,
            'warehouse_id'      => $warehouse_id,
            'type'              => $type,
            'qty'               => $qty,
            'date_log'          => date("Y-m-d"),
            'description'       => $description,
            'key_id'            => $key_id,
            'exc_reseller_id'   => $exc_reseller_id,
            'store_id'          => getStoreId(),
            'created_user'      => Auth::User()->employee->employee_name,
        ]);

    }

    function getLogStock($exc_reseller_id, $product_id){

        $stockList = LogStockExclusiveReseller::where('product_id', $product_id)->where('exc_reseller_id', $exc_reseller_id)->with('product', 'warehouse')->get();
        return['data'=> $stockList];
    }

    function getLogStockByPeriode($exc_reseller_id, $product_id, $month, $year){

        $date = date($year."-".$month."-1");
        $datefrom = date("Y-m-1", strtotime($date));
        $dateto = date("Y-m-t", strtotime($date));

        $stockList = LogStockExclusiveReseller::where('product_id', $product_id)
                                    ->where('exc_reseller_id', $exc_reseller_id)
                                    ->whereBetween('date_log', [$datefrom, $dateto])
                                    ->with('product', 'warehouse')->get();
        return['data'=> $stockList];
    }

    //PRODUCT SUPPLIER
    function addLogStockINSupplier($exc_reseller_id, $product_supplier_id, $warehouse_id, $qty, $description, $key_id){
        $this->justAddLogSupplier($exc_reseller_id, $product_supplier_id, $warehouse_id, $qty, 'IN', $description, $key_id);
    }

    function addLogStockOUTSupplier($exc_reseller_id, $product_supplier_id, $warehouse_id, $qty, $description, $key_id){
        $this->justAddLogSupplier($exc_reseller_id, $product_supplier_id, $warehouse_id, $qty, 'OUT', $description, $key_id);
    }

    function deleteLogStockSupplier($exc_reseller_id, $product_supplier_id, $warehouse_id, $key_id){
        LogStockExclusiveReseller::where('key_id', $key_id)
                    ->where('exc_reseller_id', $exc_reseller_id )
                    ->where('product_supplier_id', $product_supplier_id )
                    ->where('warehouse_id', $warehouse_id )
                    ->delete();
    }

    function justAddLogSupplier($exc_reseller_id, $product_supplier_id, $warehouse_id, $qty, $type, $description, $key_id){
        LogStockExclusiveReseller::create([
            'product_supplier_id'        => $product_supplier_id,
            'warehouse_id'      => $warehouse_id,
            'type'              => $type,
            'qty'               => $qty,
            'date_log'          => date("Y-m-d"),
            'description'       => $description,
            'key_id'            => $key_id,
            'exc_reseller_id'   => $exc_reseller_id,
            'store_id'          => getStoreId(),
            'created_user'      => Auth::User()->employee->employee_name,
        ]);
    }

    function getLogStockSupplier($exc_reseller_id, $product_supplier_id){

        $stockList = LogStockExclusiveReseller::where('product_supplier_id', $product_supplier_id)->where('exc_reseller_id', $exc_reseller_id)->with('product', 'warehouse')->get();
        return['data'=> $stockList];
    }

    function getLogStockByPeriodeSupplier($exc_reseller_id, $product_supplier_id, $month, $year){

        $date = date($year."-".$month."-1");
        $datefrom = date("Y-m-1", strtotime($date));
        $dateto = date("Y-m-t", strtotime($date));

        $stockList = LogStockExclusiveReseller::where('product_supplier_id', $product_supplier_id)
                                    ->where('exc_reseller_id', $exc_reseller_id)
                                    ->whereBetween('date_log', [$datefrom, $dateto])
                                    ->with('productSupplier', 'warehouse')->get();
        return['data'=> $stockList];
    }
}
