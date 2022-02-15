<?php

namespace App\Models\MazuLog;

use App\Models\MazuMaster\Product;
use App\Models\MazuMaster\ProductSupplier;
use App\Models\MazuMaster\Warehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogStock extends Model
{
    use HasFactory;
    protected $table = 'tl_stock';
    protected $primaryKey = 'log_stock_id';
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
}
