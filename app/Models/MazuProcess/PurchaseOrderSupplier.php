<?php

namespace App\Models\MazuProcess;

use App\Models\MazuMaster\Supplier;
use Illuminate\Database\Eloquent\Model;
use App\Models\MazuProcess\PurchaseOrderSupplierItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrderSupplier extends Model
{
    use HasFactory;
    protected $table = 'tp_po_supplier';
    protected $primaryKey = 'po_supplier_id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [

    ];

    // public function supplier()
    // {
    //     return $this->hasOne(Supplier::class, 'supplier_id', 'supplier_id');
    // }

    // public function items()
    // {
    //     return $this->hasMany(PurchaseOrderSupplierItem::class, 'po_supplier_id', 'po_supplier_id')->orderBy('order_item', 'ASC');
    // }
}
