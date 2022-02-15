<?php

namespace App\Models\MazuMaster;

use App\Models\MazuMaster\Stock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductSupplier extends Model
{
    protected $table = 'tm_product_supplier';
    protected $primaryKey = 'product_supplier_id';
    public $incrementing = false;
    public $keyType = 'string';
    protected $guarded = [

    ];

    public function category()
    {
        return $this->hasOne(ProductCategory::class, 'product_category_id', 'product_category_id');
    }

    public function unit()
    {
        return $this->hasOne(Unit::class, 'unit_id', 'unit_id');
    }
    public function stockWarehouse()
    {
        return $this->hasOne(Stock::class, 'product_supplier_id', 'product_supplier_id');
    }
}
