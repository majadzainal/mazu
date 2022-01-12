<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use App\Models\Master\Location;

class Plant extends Model
{
    protected $primaryKey = 'plant_id';
    public $incrementing = false;
    protected $table = 'tm_plant';
    protected $guarded = [

    ];

    public function location()
    {
        return $this->hasOne(Location::class, 'location_id', 'location_id');
    }
}
