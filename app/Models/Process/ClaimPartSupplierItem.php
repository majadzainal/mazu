<?php

namespace App\Models\Process;

use App\Models\Part\PartSupplier;
use Illuminate\Database\Eloquent\Model;
use App\Models\Process\ClaimPartSupplier;
use App\Models\Process\ClaimPartSupplierItemNg;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClaimPartSupplierItem extends Model
{
    protected $primaryKey = 'claim_psupplier_item_id';
    public $incrementing = false;
    protected $table = 'tp_claim_part_supplier_item';
    protected $guarded = [

    ];


    public function claimPart()
    {
        return $this->belongsTo(ClaimPartSupplier::class, 'claim_id', 'claim_id');
    }

    public function partSupplier()
    {
        return $this->belongsTo(PartSupplier::class, 'part_supplier_id', 'part_supplier_id');
    }

    public function claimPartItemNG()
    {
        return $this->belongsTo(ClaimPartSupplierItemNg::class, 'claim_psupplier_item_id', 'claim_psupplier_item_id');
    }
}
