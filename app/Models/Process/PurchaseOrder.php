<?php

namespace App\Models\Process;

use App\Models\Master\Supplier;
use App\Models\Master\StatusProcess;
use App\Models\Process\POItem;
use App\Models\Log\LogPO;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrder extends Model
{
    use HasFactory;
    protected $table = 'tp_purchase_order';
    protected $primaryKey = 'po_id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [

    ];

    public function supplier()
    {
        return $this->hasOne(Supplier::class, 'supplier_id', 'supplier_id');
    }

    public function po_items()
    {
        return $this->hasMany(POItem::class, 'po_id', 'po_id')->where('is_active', 1);
    }

    public function status_po()
    {
        return $this->hasOne(StatusProcess::class, 'status_process_id', 'status_process')->where('is_active', 1);
    }

    public function log_po()
    {
        return $this->hasMany(LogPO::class, 'po_id', 'po_id');
    }
}
