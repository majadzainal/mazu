<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use App\Models\Master\Plant;

class Warehouse extends Model
{
    protected $primaryKey = 'warehouse_id';
    public $incrementing = false;
    protected $table = 'tm_warehouse';
    protected $guarded = [

    ];

    public function plant()
    {
        return $this->hasOne(Plant::class, 'plant_id', 'plant_id');
    }
}
