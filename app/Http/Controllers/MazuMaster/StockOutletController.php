<?php

namespace App\Http\Controllers\MazuMaster;

use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\MazuMaster\StockOutlet;
use App\Http\Controllers\MazuLog\LogStockOutletController;

class StockOutletController extends Controller
{
    public $objLogStockOutlet;
    public function __construct()
    {
        $this->objLogStockOutlet = new LogStockOutletController();
    }

    function plusStock($outlet_id, $product_id, $warehouse_id, $qty, $description, $key_id){
        $stockExist = StockOutlet::where('product_id',$product_id)
                    ->where('outlet_id', $outlet_id)
                    ->where('warehouse_id', $warehouse_id)->get()->first();
        if($stockExist){
            $stock = floatval($stockExist->stock) + floatval($qty);
            $stockExist->update([
                'stock'             => $stock,
                'updated_user'      => Auth::User()->employee->employee_name,
            ]);

            $this->objLogStockOutlet->addLogStockIN($outlet_id, $product_id, $warehouse_id, $qty, $description, $key_id);
        }else{
            $stockId = Uuid::uuid4()->toString();
            $stock = StockOutlet::create([
                'stock_outlet_id'   => $stockId,
                'product_id'        => $product_id,
                'stock'             => $qty,
                'warehouse_id'      => $warehouse_id,
                'outlet_id'         => $outlet_id,
                'store_id'          => getStoreId(),
                'created_user'      => Auth::User()->employee->employee_name,
            ]);

            $this->objLogStockOutlet->addLogStockIN($outlet_id, $product_id, $warehouse_id, $qty, $description, $key_id);
        }

    }

    function minStock($outlet_id, $product_id, $warehouse_id, $qty, $description, $key_id){
        $stockExist = StockOutlet::where('product_id',$product_id)
                    ->where('outlet_id', $outlet_id)
                    ->where('warehouse_id', $warehouse_id)->get()->first();
        if($stockExist){
            $stock = floatval($stockExist->stock) - floatval($qty);
            $stockExist->update([
                'stock'             => $stock,
                'updated_user'      => Auth::User()->employee->employee_name,
            ]);

            $this->objLogStockOutlet->addLogStockOUT($outlet_id, $product_id, $warehouse_id, $qty, $description, $key_id);
        }
    }

    function deleteStock($outlet_id, $product_id, $warehouse_id, $qty, $key_id){
        $stockExist = StockOutlet::where('product_id',$product_id)
                    ->where('outlet_id', $outlet_id)
                    ->where('warehouse_id', $warehouse_id)->get()->first();
        if($stockExist){
            $stock = floatval($stockExist->stock) - floatval($qty);
            $stockExist->update([
                'stock'             => $stock,
                'updated_user'      => Auth::User()->employee->employee_name,
            ]);

            $this->objLogStockOutlet->deleteLogStock($outlet_id, $product_id, $warehouse_id, $key_id);
        }
    }


    //PRODUCT SUPPLIER
    function plusStockSupplier($outlet_id, $product_supplier_id, $warehouse_id, $qty, $description, $key_id){
        $stockExist = StockOutlet::where('product_supplier_id',$product_supplier_id)
                    ->where('outlet_id', $outlet_id)
                    ->where('warehouse_id', $warehouse_id)->get()->first();
        if($stockExist){
            $stock = floatval($stockExist->stock) + floatval($qty);
            $stockExist->update([
                'stock'             => $stock,
                'updated_user'      => Auth::User()->employee->employee_name,
            ]);

            $this->objLogStockOutlet->addLogStockINSupplier($outlet_id, $product_supplier_id, $warehouse_id, $qty, $description, $key_id);
        }else{
            $stockId = Uuid::uuid4()->toString();
            $stock = StockOutlet::create([
                'stock_outlet_id'   => $stockId,
                'product_supplier_id'        => $product_supplier_id,
                'stock'             => $qty,
                'warehouse_id'      => $warehouse_id,
                'outlet_id'         => $outlet_id,
                'store_id'          => getStoreId(),
                'created_user'      => Auth::User()->employee->employee_name,
            ]);

            $this->objLogStockOutlet->addLogStockINSupplier($outlet_id, $product_supplier_id, $warehouse_id, $qty, $description, $key_id);
        }

    }

    function minStockSupplier($outlet_id, $product_supplier_id, $warehouse_id, $qty, $description, $key_id){
        $stockExist = StockOutlet::where('product_supplier_id',$product_supplier_id)
                    ->where('outlet_id', $outlet_id)
                    ->where('warehouse_id', $warehouse_id)->get()->first();
        if($stockExist){
            $stock = floatval($stockExist->stock) - floatval($qty);
            $stockExist->update([
                'stock'             => $stock,
                'updated_user'      => Auth::User()->employee->employee_name,
            ]);

            $this->objLogStockOutlet->addLogStockOUTSupplier($outlet_id, $product_supplier_id, $warehouse_id, $qty, $description, $key_id);
        }
    }

    function deleteStockSupplier($outlet_id, $product_supplier_id, $warehouse_id, $qty, $key_id){
        $stockExist = StockOutlet::where('product_supplier_id',$product_supplier_id)
                    ->where('outlet_id', $outlet_id)
                    ->where('warehouse_id', $warehouse_id)->get()->first();
        if($stockExist){
            $stock = floatval($stockExist->stock) - floatval($qty);
            $stockExist->update([
                'stock'             => $stock,
                'updated_user'      => Auth::User()->employee->employee_name,
            ]);

            $this->objLogStockOutlet->deleteLogStockSupplier($outlet_id, $product_supplier_id, $warehouse_id, $key_id);
        }
    }
}
