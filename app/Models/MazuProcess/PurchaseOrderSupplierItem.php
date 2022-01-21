<?php

namespace App\Models\MazuProcess;

use App\Models\MazuMaster\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderSupplierItem extends Model
{
    protected $primaryKey = 'po_supplier_item_id';
    public $incrementing = false;
    protected $table = 'tp_po_supplier_item';
    protected $guarded = [

    ];


    // public function product()
    // {
    //     return $this->belongsTo(Product::class, 'product_id', 'product_id');
    // }
}
