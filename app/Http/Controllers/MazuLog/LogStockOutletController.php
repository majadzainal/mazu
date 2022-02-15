<?php

namespace App\Http\Controllers\MazuLog;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\MazuLog\LogStockOutlet;

class LogStockOutletController extends Controller
{
    function addLogStockIN($outlet_id, $product_id, $warehouse_id, $qty, $description, $key_id){
        $this->justAddLog($outlet_id, $product_id, $warehouse_id, $qty, 'IN', $description, $key_id);
    }

    function addLogStockOUT($outlet_id, $product_id, $warehouse_id, $qty, $description, $key_id){
        $this->justAddLog($outlet_id, $product_id, $warehouse_id, $qty, 'OUT', $description, $key_id);
    }

    function deleteLogStock($outlet_id, $product_id, $warehouse_id, $key_id){

        LogStockOutlet::where('key_id', $key_id)
                    ->where('outlet_id', $outlet_id )
                    ->where('product_id', $product_id )
                    ->where('warehouse_id', $warehouse_id )
                    ->delete();
    }

    function justAddLog($outlet_id, $product_id, $warehouse_id, $qty, $type, $description, $key_id){
        LogStockOutlet::create([
            'product_id'        => $product_id,
            'warehouse_id'      => $warehouse_id,
            'type'              => $type,
            'qty'               => $qty,
            'date_log'          => date("Y-m-d"),
            'description'       => $description,
            'key_id'         => $key_id,
            'outlet_id'         => $outlet_id,
            'store_id'          => getStoreId(),
            'created_user'      => Auth::User()->employee->employee_name,
        ]);
    }

    function getLogStock($outlet_id, $product_id){

        $stockList = LogStockOutlet::where('product_id', $product_id)->where('outlet_id', $outlet_id)->with('product', 'warehouse')->get();
        return['data'=> $stockList];
    }

    function getLogStockByPeriode($outlet_id, $product_id, $month, $year){

        $date = date($year."-".$month."-1");
        $datefrom = date("Y-m-1", strtotime($date));
        $dateto = date("Y-m-t", strtotime($date));

        $stockList = LogStockOutlet::where('product_id', $product_id)
                                    ->where('outlet_id', $outlet_id)
                                    ->whereBetween('date_log', [$datefrom, $dateto])
                                    ->with('product', 'warehouse')->get();
        return['data'=> $stockList];
    }

    //PRODUCT SUPPLIER
    function addLogStockINSupplier($outlet_id, $product_supplier_id, $warehouse_id, $qty, $description, $key_id){
        $this->justAddLogSupplier($outlet_id, $product_supplier_id, $warehouse_id, $qty, 'IN', $description, $key_id);
    }

    function addLogStockOUTSupplier($outlet_id, $product_supplier_id, $warehouse_id, $qty, $description, $key_id){
        $this->justAddLogSupplier($outlet_id, $product_supplier_id, $warehouse_id, $qty, 'OUT', $description, $key_id);
    }

    function deleteLogStockSupplier($outlet_id, $product_supplier_id, $warehouse_id, $key_id){

        LogStockOutlet::where('key_id', $key_id)
                    ->where('outlet_id', $outlet_id )
                    ->where('product_supplier_id', $product_supplier_id )
                    ->where('warehouse_id', $warehouse_id )
                    ->delete();
    }

    function justAddLogSupplier($outlet_id, $product_supplier_id, $warehouse_id, $qty, $type, $description, $key_id){
        LogStockOutlet::create([
            'product_supplier_id'        => $product_supplier_id,
            'warehouse_id'      => $warehouse_id,
            'type'              => $type,
            'qty'               => $qty,
            'date_log'          => date("Y-m-d"),
            'description'       => $description,
            'key_id'         => $key_id,
            'outlet_id'         => $outlet_id,
            'store_id'          => getStoreId(),
            'created_user'      => Auth::User()->employee->employee_name,
        ]);
    }

    function getLogStockSupplier($outlet_id, $product_supplier_id){

        $stockList = LogStockOutlet::where('product_supplier_id', $product_supplier_id)->where('outlet_id', $outlet_id)->with('productSupplier', 'warehouse')->get();
        return['data'=> $stockList];
    }

    function getLogStockByPeriodeSupplier($outlet_id, $product_supplier_id, $month, $year){

        $date = date($year."-".$month."-1");
        $datefrom = date("Y-m-1", strtotime($date));
        $dateto = date("Y-m-t", strtotime($date));

        $stockList = LogStockOutlet::where('product_supplier_id', $product_supplier_id)
                                    ->where('outlet_id', $outlet_id)
                                    ->whereBetween('date_log', [$datefrom, $dateto])
                                    ->with('productSupplier', 'warehouse')->get();
        return['data'=> $stockList];
    }
}
