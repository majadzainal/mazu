<?php

namespace App\Http\Controllers\MazuMaster;

use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Models\MazuMaster\Stock;
use App\Models\MazuMaster\Product;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MazuLog\LogStockController;
use App\Models\MazuMaster\ProductSupplier;

class StockController extends Controller
{
    public $objLogStock;
    public function __construct()
    {
        $this->objLogStock = new LogStockController();
    }

    function plusStock($product_id, $warehouse_id, $qty, $description){
        $stockExist = Stock::where('product_id',$product_id)->get()->first();
        if($stockExist){
            $stock = floatval($stockExist->stock) + floatval($qty);
            $stockExist->update([
                'stock'             => $stock,
                'updated_user'      => Auth::User()->employee->employee_name,
            ]);

            $this->objLogStock->addLogStockIN($product_id, $stockExist->warehouse_id, $qty, $description);

            $product = Product::where('product_id',$product_id)->get()->first();
            $stock = floatval($product->stock) + floatval($qty);
            $product->update([
                'stock'             => $stock,
            ]);
        }else{
            $stockId = Uuid::uuid4()->toString();
            $stock = Stock::create([
                'stock_id'          => $stockId,
                'product_id'        => $product_id,
                'stock'             => $qty,
                'warehouse_id'      => $warehouse_id,
                'store_id'          => getStoreId(),
                'created_user'      => Auth::User()->employee->employee_name,
            ]);

            $this->objLogStock->addLogStockIN($product_id, $warehouse_id, $qty, $description);
            $product = Product::where('product_id',$product_id)->get()->first();
            $stock = floatval($product->stock) + floatval($qty);
            $product->update([
                'stock'             => $stock,
            ]);
        }

    }

    function minStock($product_id, $warehouse_id, $qty, $description){
        $stockExist = Stock::where('product_id',$product_id)->get()->first();
        if($stockExist){
            $stock = floatval($stockExist->stock) - floatval($qty);
            $stockExist->update([
                'stock'             => $stock,
                'updated_user'      => Auth::User()->employee->employee_name,
            ]);

            $this->objLogStock->addLogStockOUT($product_id, $stockExist->warehouse_id, $qty, $description);

            $product = Product::where('product_id',$product_id)->get()->first();
            $stock = $product->stock - $qty;
            $product->update([
                'stock'             => $stock,
            ]);
        }
    }
    function plusStockSupplier($product_supplier_id, $warehouse_id, $qty, $description){
        $stockExist = Stock::where('product_supplier_id', $product_supplier_id)
                    ->where('warehouse_id', $warehouse_id)->get()->first();
        if($stockExist){
            $stock = floatval($stockExist->stock) + floatval($qty);
            $stockExist->update([
                'stock'             => $stock,
                'updated_user'      => Auth::User()->employee->employee_name,
            ]);

            $this->objLogStock->addLogStockINSupplier($product_supplier_id, $warehouse_id, $qty, $description);

            $product = ProductSupplier::find($product_supplier_id);
            $stock = floatval($product->stock) + floatval($qty);
            $product->update([
                'stock'             => $stock,
            ]);
        }else{
            $stockId = Uuid::uuid4()->toString();
            $stock = Stock::create([
                'stock_id'                  => $stockId,
                'product_supplier_id'       => $product_supplier_id,
                'stock'                     => $qty,
                'warehouse_id'              => $warehouse_id,
                'store_id'                  => getStoreId(),
                'created_user'              => Auth::User()->employee->employee_name,
            ]);

            $this->objLogStock->addLogStockINSupplier($product_supplier_id, $warehouse_id, $qty, $description);
            $product = ProductSupplier::find($product_supplier_id);
            $stock = floatval($product->stock) + floatval($qty);
            $product->update([
                'stock'             => $stock,
            ]);
        }

    }

    function minStockSupplier($product_supplier_id, $warehouse_id, $qty, $description){
        $stockExist = Stock::where('product_supplier_id',$product_supplier_id)
                    ->where('warehouse_id', $warehouse_id)->get()->first();
        if($stockExist){
            $stock = floatval($stockExist->stock) - floatval($qty);
            $stockExist->update([
                'stock'             => $stock,
                'updated_user'      => Auth::User()->employee->employee_name,
            ]);

            $this->objLogStock->addLogStockOUTSupplier($product_supplier_id, $warehouse_id, $qty, $description);

            $product = ProductSupplier::find($product_supplier_id);
            $stock = $product->stock - $qty;
            $product->update([
                'stock'             => $stock,
            ]);
        }
    }
}
