<?php

namespace App\Models\Production;

use App\Models\Part\PartCustomer;
use App\Models\Part\BillMaterial;
use App\Models\Process\SalesOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductionSchedule extends Model
{
    use HasFactory;
    protected $table = 'tp_production_schedule';
    protected $primaryKey = 'schedule_id';
    public $incrementing = false;
    protected $guarded = [

    ];

    public function part_customer()
    {
        return $this->hasOne(PartCustomer::class, 'part_customer_id', 'part_customer_id');
    }

    public function bom()
    {
        return $this->hasOne(BillMaterial::class, 'part_customer_id', 'part_customer_id');
    }
    
}
