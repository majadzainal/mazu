<?php

namespace App\Models\MazuLog;

use App\Models\MazuMaster\Outlet;
use App\Models\MazuMaster\Product;
use App\Models\MazuMaster\Warehouse;
use Illuminate\Database\Eloquent\Model;
use App\Models\MazuMaster\ProductSupplier;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogStockOutlet extends Model
{
    use HasFactory;
    protected $table = 'tl_stock_outlet';
    protected $primaryKey = 'log_stock_outlet_id';
    public $incrementing = false;
    protected $guarded = [

    ];

    public function product()
    {
        return $this->hasOne(Product::class, 'product_id', 'product_id');
    }

    public function productSupplier()
    {
        return $this->hasOne(ProductSupplier::class, 'product_supplier_id', 'product_supplier_id');
    }

    public function warehouse()
    {
        return $this->hasOne(Warehouse::class, 'warehouse_id', 'warehouse_id');
    }

    public function outlet()
    {
        return $this->hasOne(Outlet::class, 'outlet_id', 'outlet_id');
    }
}
