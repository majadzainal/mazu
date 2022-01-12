<?php

namespace App\Models\Process;

use App\Models\Master\Supplier;
use App\Models\Process\RecPartSupplier;
use Illuminate\Database\Eloquent\Model;
use App\Models\Production\RequestRawmat;
use App\Models\Process\ClaimPartSupplierItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClaimPartSupplier extends Model
{
    use HasFactory;
    protected $table = 'tp_claim_part_supplier';
    protected $primaryKey = 'claim_id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [

    ];

    public function item()
    {
        return $this->hasOne(ClaimPartSupplierItem::class, 'claim_id', 'claim_id');
    }
    public function supplier()
    {
        return $this->hasOne(Supplier::class, 'supplier_id', 'supplier_id');
    }
    public function recPart()
    {
        return $this->hasOne(RecPartSupplier::class, 'recpart_supplier_id', 'recpart_supplier_id');
    }
    public function reqRawMaterial()
    {
        return $this->hasOne(RequestRawmat::class, 'request_id', 'request_id');
    }
}
