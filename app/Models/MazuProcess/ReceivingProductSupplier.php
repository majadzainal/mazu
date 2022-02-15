<?php

namespace App\Models\MazuProcess;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivingProductSupplier extends Model
{
    use HasFactory;
    protected $table = 'tp_rec_prod_supplier';
    protected $primaryKey = 'rec_prod_supplier_id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [

    ];

    public function poMaterial()
    {
        return $this->hasOne(PurchaseOrderMaterial::class, 'po_material_id', 'po_material_id');
    }

    public function items()
    {
        return $this->hasMany(ReceivingProductSupplierItem::class, 'rec_prod_supplier_id', 'rec_prod_supplier_id');
    }
}
