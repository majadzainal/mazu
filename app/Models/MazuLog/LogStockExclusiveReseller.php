<?php

namespace App\Models\MazuLog;

use App\Models\MazuMaster\Product;
use App\Models\MazuMaster\Warehouse;
use Illuminate\Database\Eloquent\Model;
use App\Models\MazuMaster\ProductSupplier;
use App\Models\MazuMaster\ExclusiveReseller;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogStockExclusiveReseller extends Model
{
    use HasFactory;
    protected $table = 'tl_stock_exc_reseller';
    protected $primaryKey = 'log_stock_exc_reseller_id';
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
    public function excReseller()
    {
        return $this->hasOne(ExclusiveReseller::class, 'exc_reseller_id', 'exc_reseller_id');
    }
}
