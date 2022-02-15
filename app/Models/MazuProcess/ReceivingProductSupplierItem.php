<?php

namespace App\Models\MazuProcess;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivingProductSupplierItem extends Model
{
    protected $primaryKey = 'rec_prod_supplier_item_id';
    public $incrementing = false;
    protected $table = 'tp_rec_prod_supplier_item';
    protected $guarded = [

    ];


    public function poItem()
    {
        return $this->belongsTo(PurchaseOrderMaterialItem::class, 'po_material_item_id', 'po_material_item_id');
    }
}
