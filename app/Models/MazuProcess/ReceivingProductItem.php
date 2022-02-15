<?php

namespace App\Models\MazuProcess;

use Illuminate\Database\Eloquent\Model;
use App\Models\MazuProcess\PurchaseOrderSupplierItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReceivingProductItem extends Model
{
    protected $primaryKey = 'receiving_product_item_id';
    public $incrementing = false;
    protected $table = 'tp_receiving_product_item';
    protected $guarded = [

    ];


    public function poItem()
    {
        return $this->belongsTo(PurchaseOrderSupplierItem::class, 'po_supplier_item_id', 'po_supplier_item_id');
    }
}
