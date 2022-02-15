<?php

namespace App\Models\MazuMaster;

use App\Models\MazuMaster\Product;
use App\Models\MazuMaster\Warehouse;
use Illuminate\Database\Eloquent\Model;
use App\Models\MazuMaster\ProductSupplier;
use App\Models\MazuMaster\ExclusiveReseller;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockExclusiveReseller extends Model
{
    protected $table = 'tm_stock_exc_reseller';
    protected $primaryKey = 'stock_exc_reseller_id';
    public $incrementing = false;
    public $keyType = 'string';
    protected $guarded = [

    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'warehouse_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function productSupplier()
    {
        return $this->hasOne(ProductSupplier::class, 'product_supplier_id', 'product_supplier_id');
    }


    public function excReseller()
    {
        return $this->belongsTo(ExclusiveReseller::class, 'exc_reseller_id', 'exc_reseller_id');
    }
}
