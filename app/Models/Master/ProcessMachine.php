<?php

namespace App\Models\Master;

use App\Models\Master\Divisi;
use App\Models\Master\Plant;
use App\Models\Master\Unit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProcessMachine extends Model
{
    public $incrementing = false;
    protected $table = 'tm_process_machine';
    protected $primaryKey = 'pmachine_id';
    protected $guarded = [

    ];

    public function divisi()
    {
        return $this->hasOne(Divisi::class, 'divisi_id', 'divisi_id');
    }

    public function plant()
    {
        return $this->hasOne(Plant::class, 'plant_id', 'plant_id');
    }

    public function unit()
    {
        return $this->hasOne(Unit::class, 'unit_id', 'spec_unit');
    }
}
