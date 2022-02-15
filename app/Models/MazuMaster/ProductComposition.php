<?php

namespace App\Models\MazuMaster;

use App\Models\MazuMaster\Product;
use Illuminate\Database\Eloquent\Model;
use App\Models\MazuMaster\ProductSupplier;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductComposition extends Model
{
    protected $primaryKey = 'product_composition_id';
    public $incrementing = false;
    protected $table = 'tm_product_composition';
    protected $guarded = [

    ];

    public function productSupplier()
    {
        return $this->belongsTo(ProductSupplier::class, 'product_supplier_id', 'product_supplier_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
