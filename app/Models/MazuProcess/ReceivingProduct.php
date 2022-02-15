<?php

namespace App\Models\MazuProcess;

use Illuminate\Database\Eloquent\Model;
use App\Models\MazuProcess\ReceivingProductItem;
use App\Models\MazuProcess\PurchaseOrderSupplier;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReceivingProduct extends Model
{
    use HasFactory;
    protected $table = 'tp_receiving_product';
    protected $primaryKey = 'receiving_product_id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [

    ];

    public function poSupplier()
    {
        return $this->hasOne(PurchaseOrderSupplier::class, 'po_supplier_id', 'po_supplier_id');
    }

    public function items()
    {
        return $this->hasMany(ReceivingProductItem::class, 'receiving_product_id', 'receiving_product_id');
    }
}
