<?php

namespace App\Http\Controllers\MazuLog;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MazuLog\LogStock;
use App\Models\MazuMaster\Product;
use App\Models\MazuMaster\ProductSupplier;
use Illuminate\Support\Facades\Auth;

class LogStockController extends Controller
{
    function addLogStockIN($product_id, $warehouse_id, $qty, $description){
        $this->justAddLog($product_id, $warehouse_id, $qty, 'IN', $description);
    }

    function addLogStockOUT($product_id, $warehouse_id, $qty, $description){
        $this->justAddLog($product_id, $warehouse_id, $qty, 'OUT', $description);
    }

    function deleteLogStock($product_id, $warehouse_id, $key_id){

        LogStock::where('description', 'like', '%' . $key_id . '%')
                    ->where('product_id', $product_id )
                    ->where('warehouse_id', $warehouse_id )
                    ->delete();
    }

    function justAddLog($product_id, $warehouse_id, $qty, $type, $description){
        $store_id = Product::where('product_id', $product_id)->pluck('store_id')->first();
        LogStock::create([
            'product_id'        => $product_id,
            'warehouse_id'      => $warehouse_id,
            'type'              => $type,
            'qty'               => $qty,
            'date_log'          => date("Y-m-d"),
            'description'       => $description,
            'store_id'          => $store_id,
            'created_user'      => Auth::User()->employee->employee_name,
        ]);
    }

    //LOG PART SUPPLIER
    function addLogStockINSupplier($product_supplier_id, $warehouse_id, $qty, $description){
        $this->justAddLogSupplier($product_supplier_id, $warehouse_id, $qty, 'IN', $description);
    }

    function addLogStockOUTSupplier($product_supplier_id, $warehouse_id, $qty, $description){
        $this->justAddLogSupplier($product_supplier_id, $warehouse_id, $qty, 'OUT', $description);
    }

    function deleteLogStockSupplier($product_supplier_id, $warehouse_id, $key_id){

        LogStock::where('description', 'like', '%' . $key_id . '%')
                    ->where('product_supplier_id', $product_supplier_id )
                    ->where('warehouse_id', $warehouse_id )
                    ->delete();
    }

    function justAddLogSupplier($product_supplier_id, $warehouse_id, $qty, $type, $description){
        $store_id = ProductSupplier::where('product_supplier_id', $product_supplier_id)->pluck('store_id')->first();
        LogStock::create([
            'product_supplier_id'        => $product_supplier_id,
            'warehouse_id'      => $warehouse_id,
            'type'              => $type,
            'qty'               => $qty,
            'date_log'          => date("Y-m-d"),
            'description'       => $description,
            'store_id'          => $store_id,
            'created_user'      => Auth::User()->employee->employee_name,
        ]);
    }



    function getLogStock($product_id){

        $stockList = LogStock::where('product_id', $product_id)->with('product', 'warehouse')->get();
        return['data'=> $stockList];
    }

    function getLogStockByPeriode($product_id, $month, $year){

        $date = date($year."-".$month."-1");
        $datefrom = date("Y-m-1", strtotime($date));
        $dateto = date("Y-m-t", strtotime($date));

        $stockList = LogStock::where('product_id', $product_id)
                                    ->whereBetween('date_log', [$datefrom, $dateto])
                                    ->with('product', 'warehouse')->get();
        return['data'=> $stockList];
    }
}
