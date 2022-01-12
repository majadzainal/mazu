<?php

namespace App\Models\Process;

use App\Models\Master\Ng;
use App\Models\Master\Warehouse;
use Illuminate\Database\Eloquent\Model;
use App\Models\Process\ClaimPartSupplierItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClaimPartSupplierItemNg extends Model
{
    protected $primaryKey = 'claim_item_ng_id';
    public $incrementing = false;
    protected $table = 'tp_claim_item_ng';
    protected $guarded = [

    ];


    public function claimPartItem()
    {
        return $this->belongsTo(ClaimPartSupplierItem::class, 'claim_psupplier_item_id', 'claim_psupplier_item_id');
    }

    public function ng()
    {
        return $this->belongsTo(Ng::class, 'ng_id', 'ng_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'warehouse_id');
    }

}
