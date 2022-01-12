<?php

namespace App\Models\Process;

use App\Models\Part\PartSupplier;
use App\Models\Process\PurchaseOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class POItem extends Model
{
    use HasFactory;
    protected $table = 'tp_po_item';
    protected $primaryKey = 'poitem_id';
    public $incrementing = false;
    protected $guarded = [

    ];

    public function purchaseOrder()
    {
        return $this->hasOne(PurchaseOrder::class, 'po_id', 'po_id');
    }

    public function partSupplier()
    {
        return $this->hasOne(PartSupplier::class, 'part_supplier_id', 'part_supplier_id');
    }

}
