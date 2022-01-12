<?php

namespace App\Models\Master;

use App\Models\Master\Employee;
use App\Models\Master\Plant;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $primaryKey = 'location_id';
    public $incrementing = false;
    protected $table = 'tm_location';
    protected $fillable = ['location_id','location_name','address','phone','email','website','is_active'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'location_id', 'location_id');
    }

    public function plant()
    {
        return $this->belongsTo(Plant::class, 'location_id', 'location_id');
    }

}
