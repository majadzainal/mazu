<?php

namespace App\Models\Process;

use App\Models\Master\Plant;
use App\Models\Master\OpnameType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpnameSchedule extends Model
{
    use HasFactory;
    protected $table = 'tp_opname_schedule';
    protected $primaryKey = 'opname_schedule_id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [

    ];

    public function plant()
    {
        return $this->hasOne(Plant::class, 'plant_id', 'plant_id');
    }
}
