<?php

namespace App\Models\MazuMaster;

use App\Models\MazuMaster\Unit;
use App\Models\MazuMaster\Stock;
use Illuminate\Database\Eloquent\Model;
use App\Models\MazuMaster\ProductCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    protected $table = 'tm_product';
    protected $primaryKey = 'product_id';
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
        return $this->hasOne(Stock::class, 'product_id', 'product_id');
    }

    public function composition()
    {
        return $this->hasMany(ProductComposition::class, 'product_id', 'product_id')->orderBy('order_item', 'ASC');
    }
}
