<?php

namespace App\Http\Controllers\MazuMaster;

use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\MazuMaster\StockExclusiveReseller;
use App\Http\Controllers\MazuLog\LogStockExcResellerController;

class StockExcResellerController extends Controller
{
    public $objLogStockExcReseller;
    public function __construct()
    {
        $this->objLogStockExcReseller = new LogStockExcResellerController();
    }

    function plusStock($exc_reseller_id, $product_id, $warehouse_id, $qty, $description, $key_id){

        $stockExist = StockExclusiveReseller::where('product_id',$product_id)
                    ->where('exc_reseller_id', $exc_reseller_id)
                    ->where('warehouse_id', $warehouse_id)->get()->first();
        if($stockExist){
            $stock = floatval($stockExist->stock) + floatval($qty);
            $stockExist->update([
                'stock'             => $stock,
                'updated_user'      => Auth::User()->employee->employee_name,
            ]);

            $this->objLogStockExcReseller->addLogStockIN($exc_reseller_id, $product_id, $warehouse_id, $qty, $description, $key_id);
        }else{
            $stock_exc_reseller_id = Uuid::uuid4()->toString();
            $stock = StockExclusiveReseller::create([
                'stock_exc_reseller_id'     => $stock_exc_reseller_id,
                'product_id'                => $product_id,
                'stock'                     => $qty,
                'warehouse_id'              => $warehouse_id,
                'exc_reseller_id'                 => $exc_reseller_id,
                'store_id'                  => getStoreId(),
                'created_user'              => Auth::User()->employee->employee_name,
            ]);
            $this->objLogStockExcReseller->addLogStockIN($exc_reseller_id, $product_id, $warehouse_id, $qty, $description, $key_id);
        }

    }

    function minStock($exc_reseller_id, $product_id, $warehouse_id, $qty, $description, $key_id){
        $stockExist = StockExclusiveReseller::where('product_id',$product_id)
                    ->where('exc_reseller_id', $exc_reseller_id)
                    ->where('warehouse_id', $warehouse_id)->get()->first();
        if($stockExist){
            $stock = floatval($stockExist->stock) - floatval($qty);
            $stockExist->update([
                'stock'             => $stock,
                'updated_user'      => Auth::User()->employee->employee_name,
            ]);

            $this->objLogStockExcReseller->addLogStockOUT($exc_reseller_id, $product_id, $warehouse_id, $qty, $description, $key_id);
        }
    }

    function deleteStock($exc_reseller_id, $product_id, $warehouse_id, $qty, $key_id){
        $stockExist = StockExclusiveReseller::where('product_id',$product_id)
                    ->where('exc_reseller_id', $exc_reseller_id)
                    ->where('warehouse_id', $warehouse_id)->get()->first();
        if($stockExist){
            $stock = floatval($stockExist->stock) - floatval($qty);
            $stockExist->update([
                'stock'             => $stock,
                'updated_user'      => Auth::User()->employee->employee_name,
            ]);

            $this->objLogStockExcReseller->deleteLogStock($exc_reseller_id, $product_id, $warehouse_id, $key_id);
        }
    }

    //PRODUCT SUPPLIER
    function plusStockSupplier($exc_reseller_id, $product_supplier_id, $warehouse_id, $qty, $description, $key_id){
        $stockExist = StockExclusiveReseller::where('product_supplier_id',$product_supplier_id)
                    ->where('exc_reseller_id', $exc_reseller_id)
                    ->where('warehouse_id', $warehouse_id)->get()->first();

        if($stockExist){
            $stock = floatval($stockExist->stock) + floatval($qty);
            $stockExist->update([
                'stock'             => $stock,
                'updated_user'      => Auth::User()->employee->employee_name,
            ]);

            $this->objLogStockExcReseller->addLogStockINSupplier($exc_reseller_id, $product_supplier_id, $warehouse_id, $qty, $description, $key_id);
        }else{
            $stock_exc_reseller_id = Uuid::uuid4()->toString();
            $stock = StockExclusiveReseller::create([
                'stock_exc_reseller_id'     => $stock_exc_reseller_id,
                'product_supplier_id'                => $product_supplier_id,
                'stock'                     => $qty,
                'warehouse_id'              => $warehouse_id,
                'exc_reseller_id'                 => $exc_reseller_id,
                'store_id'                  => getStoreId(),
                'created_user'              => Auth::User()->employee->employee_name,
            ]);

            $this->objLogStockExcReseller->addLogStockINSupplier($exc_reseller_id, $product_supplier_id, $warehouse_id, $qty, $description, $key_id);
        }

    }

    function minStockSupplier($exc_reseller_id, $product_supplier_id, $warehouse_id, $qty, $description, $key_id){
        $stockExist = StockExclusiveReseller::where('product_supplier_id',$product_supplier_id)
                    ->where('exc_reseller_id', $exc_reseller_id)
                    ->where('warehouse_id', $warehouse_id)->get()->first();
        if($stockExist){
            $stock = floatval($stockExist->stock) - floatval($qty);
            $stockExist->update([
                'stock'             => $stock,
                'updated_user'      => Auth::User()->employee->employee_name,
            ]);

            $this->objLogStockExcReseller->addLogStockOUTSupplier($exc_reseller_id, $product_supplier_id, $warehouse_id, $qty, $description, $key_id);
        }
    }

    function deleteStockSupplier($exc_reseller_id, $product_supplier_id, $warehouse_id, $qty, $key_id){
        $stockExist = StockExclusiveReseller::where('product_supplier_id',$product_supplier_id)
                    ->where('exc_reseller_id', $exc_reseller_id)
                    ->where('warehouse_id', $warehouse_id)->get()->first();
        if($stockExist){
            $stock = floatval($stockExist->stock) - floatval($qty);
            $stockExist->update([
                'stock'             => $stock,
                'updated_user'      => Auth::User()->employee->employee_name,
            ]);

            $this->objLogStockExcReseller->deleteLogStockSupplier($exc_reseller_id, $product_supplier_id, $warehouse_id, $key_id);
        }
    }
}
