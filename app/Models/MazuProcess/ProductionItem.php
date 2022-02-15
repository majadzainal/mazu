<?php

namespace App\Models\MazuProcess;

use App\Models\MazuMaster\ProductSupplier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionItem extends Model
{
    protected $primaryKey = 'production_item_id';
    public $incrementing = false;
    protected $table = 'tp_production_item';
    protected $guarded = [

    ];


    public function product()
    {
        return $this->belongsTo(ProductSupplier::class, 'product_supplier_id', 'product_supplier_id');
    }
}
