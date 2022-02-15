<?php

namespace App\Models\MazuMaster;

use Illuminate\Database\Eloquent\Model;
use App\Models\MazuMaster\ProductSupplier;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockOutlet extends Model
{
    protected $table = 'tm_stock_outlet';
    protected $primaryKey = 'stock_outlet_id';
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


    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id', 'outlet_id');
    }
}
