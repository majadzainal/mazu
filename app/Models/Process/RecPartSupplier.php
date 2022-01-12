<?php

namespace App\Models\Process;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecPartSupplier extends Model
{
    protected $table = 'tp_recpart_supplier';
    protected $primaryKey = 'recpart_supplier_id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [

    ];

    public function po()
    {
        return $this->hasOne(PurchaseOrder::class, 'po_id', 'po_id');
    }

    public function recpart_item()
    {
        return $this->hasMany(RecPartSupplierItem::class, 'recpart_supplier_id', 'recpart_supplier_id');
    }
}
