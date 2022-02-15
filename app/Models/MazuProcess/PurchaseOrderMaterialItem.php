<?php

namespace App\Models\MazuProcess;

use App\Models\MazuMaster\ProductSupplier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderMaterialItem extends Model
{
    protected $primaryKey = 'po_material_item_id';
    public $incrementing = false;
    protected $table = 'tp_po_material_item';
    protected $guarded = [

    ];

    public function productSupplier()
    {
        return $this->belongsTo(ProductSupplier::class, 'product_supplier_id', 'product_supplier_id');
    }
}
