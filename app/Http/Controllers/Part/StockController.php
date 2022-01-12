<?php

namespace App\Http\Controllers\Part;

use Ramsey\Uuid\Uuid;
use App\Models\Part\Stock;
use Illuminate\Http\Request;
use App\Models\Master\Supplier;
use App\Models\Part\PartCustomer;
use App\Models\Part\PartSupplier;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\Log\LogPartStockController;
use App\Http\Controllers\Part\PartSupplierController;

class StockController extends Controller
{
    public $objLogPartStock;
    public function __construct()
    {
        $this->objLogPartStock = new LogPartStockController();
    }

    function plusStockPartSupplier($part_supplier_id, $warehouse_id, $qty, $description){
        $stockExist = Stock::where('part_supplier_id',$part_supplier_id)
                    ->where('warehouse_id', $warehouse_id)->get()->first();
        if($stockExist){
            $stock = floatval($stockExist->stock) + floatval($qty);
            $stockExist->update([
                'stock'             => $stock,
                'updated_user'      => Auth::User()->employee->employee_name,
            ]);

            $this->objLogPartStock->addLogStockIN($part_supplier_id, null, $warehouse_id, $qty, $description);

            $part = PartSupplier::find($part_supplier_id);
            $stock = floatval($part->stock) + floatval($qty);
            $part->update([
                'stock'             => $stock,
            ]);
        }else{
            $stockId = Uuid::uuid4()->toString();
            $stock = Stock::create([
                'stock_id'          => $stockId,
                'part_supplier_id'  => $part_supplier_id,
                'stock'             => $qty,
                'warehouse_id'      => $warehouse_id,
                'created_user'      => Auth::User()->employee->employee_name,
            ]);

            $this->objLogPartStock->addLogStockIN($part_supplier_id, null, $warehouse_id, $qty, $description);
            $part = PartSupplier::find($part_supplier_id);
            $stock = floatval($part->stock) + floatval($qty);
            $part->update([
                'stock'             => $stock,
            ]);
        }

    }

    function minStockPartSupplier($part_supplier_id, $warehouse_id, $qty, $description){
        $stockExist = Stock::where('part_supplier_id',$part_supplier_id)
                    ->where('warehouse_id', $warehouse_id)->get()->first();
        if($stockExist){
            $stock = floatval($stockExist->stock) - floatval($qty);
            $stockExist->update([
                'stock'             => $stock,
                'updated_user'      => Auth::User()->employee->employee_name,
            ]);

            $this->objLogPartStock->addLogStockOUT($part_supplier_id, null, $warehouse_id, $qty, $description);

            $part = PartSupplier::find($part_supplier_id);
            $stock = $part->stock - $qty;
            $part->update([
                'stock'             => $stock,
            ]);
        }
    }


    function plusStockPartCustomer($part_customer_id, $warehouse_id, $qty, $description){
        $stockExist = Stock::where('part_customer_id', $part_customer_id)
                    ->where('warehouse_id', $warehouse_id)->get()->first();
        if($stockExist){
            $stock = floatval($stockExist->stock) + floatval($qty);
            $stockExist->update([
                'stock'             => $stock,
                'updated_user'      => Auth::User()->employee->employee_name,
            ]);

            $this->objLogPartStock->addLogStockIN(null, $part_customer_id, $warehouse_id, $qty, $description);

            $part = PartCustomer::find($part_customer_id);
            $stock = floatval($part->stock) + floatval($qty);
            $part->update([
                'stock'             => $stock,
            ]);
        }else{
            $stockId = Uuid::uuid4()->toString();
            $stock = Stock::create([
                'stock_id'          => $stockId,
                'part_customer_id'  => $part_customer_id,
                'stock'             => $qty,
                'warehouse_id'      => $warehouse_id,
                'created_user'      => Auth::User()->employee->employee_name,
            ]);

            $this->objLogPartStock->addLogStockIN(null, $part_customer_id, $warehouse_id, $qty, $description);
            $part = PartCustomer::find($part_customer_id);
            $stock = floatval($part->stock) + floatval($qty);
            $part->update([
                'stock'             => $stock,
            ]);
        }

    }

    function minStockPartCustomer($part_customer_id, $warehouse_id, $qty, $description){
        $stockExist = Stock::where('part_customer_id',$part_customer_id)
                    ->where('warehouse_id', $warehouse_id)->get()->first();
        if($stockExist){
            $stock = floatval($stockExist->stock) - floatval($qty);
            $stockExist->update([
                'stock'             => $stock,
                'updated_user'      => Auth::User()->employee->employee_name,
            ]);

            $this->objLogPartStock->addLogStockOUT(null, $part_customer_id, $warehouse_id, $qty, $description);

            $part = PartCustomer::find($part_customer_id);
            $stock = $part->stock - $qty;
            $part->update([
                'stock'             => $stock,
            ]);
        }
    }

    function cancelStockPartCustomerRequestRawMat($part_customer_id, $warehouse_id, $qty, $request_id){
        $stockExist = Stock::where('part_customer_id',$part_customer_id)
                    ->where('warehouse_id', $warehouse_id)->get()->first();
        if($stockExist){
            $stock = floatval($stockExist->stock) + floatval($qty);
            $stockExist->update([
                'stock'             => $stock,
                'updated_user'      => Auth::User()->employee->employee_name,
            ]);

            $this->objLogPartStock->deleteLogStock(null, $part_customer_id, $warehouse_id, $request_id);

            $part = PartCustomer::find($part_customer_id);
            $stock = $part->stock + $qty;
            $part->update([
                'stock'             => $stock,
            ]);
        }
    }

    function cancelStockPartSupplierRequestRawMat($part_supplier_id, $warehouse_id, $qty, $request_id){
        $stockExist = Stock::where('part_supplier_id',$part_supplier_id)
                    ->where('warehouse_id', $warehouse_id)->get()->first();
        if($stockExist){
            $stock = floatval($stockExist->stock) + floatval($qty);
            $stockExist->update([
                'stock'             => $stock,
                'updated_user'      => Auth::User()->employee->employee_name,
            ]);

            $this->objLogPartStock->deleteLogStock($part_supplier_id, null, $warehouse_id, $request_id);

            $part = PartSupplier::find($part_supplier_id);
            $stock = $part->stock + $qty;
            $part->update([
                'stock'             => $stock,
            ]);
        }
    }
}
