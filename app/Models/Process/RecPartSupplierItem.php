<?php

namespace App\Models\Process;

use App\Models\Master\Warehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RecPartSupplierItem extends Model
{
    use HasFactory;
    protected $table = 'tp_recpart_item';
    protected $primaryKey = 'recpart_item_id';
    public $incrementing = false;
    protected $guarded = [

    ];

    public function po_item()
    {
        return $this->hasOne(POItem::class, 'poitem_id', 'poitem_id');
    }

    public function warehouse()
    {
        return $this->hasOne(Warehouse::class, 'warehouse_id', 'warehouse_id')->where('is_active', 1);
    }
}
