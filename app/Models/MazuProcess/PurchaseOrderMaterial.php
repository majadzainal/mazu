<?php

namespace App\Models\MazuProcess;

use App\Models\MazuMaster\Supplier;
use Illuminate\Database\Eloquent\Model;
use App\Models\MazuProcess\PurchaseOrderMaterialItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrderMaterial extends Model
{
    use HasFactory;
    protected $table = 'tp_po_material';
    protected $primaryKey = 'po_material_id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [

    ];

    public function supplier()
    {
        return $this->hasOne(Supplier::class, 'supplier_id', 'supplier_id');
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderMaterialItem::class, 'po_material_id', 'po_material_id')->orderBy('order_item', 'ASC');
    }
}
