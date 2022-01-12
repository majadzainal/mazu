<?php

namespace App\Models\Master;

use App\Models\User;
use App\Models\Master\Divisi;
use App\Models\Master\Location;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $primaryKey = 'employee_id';
    public $incrementing = false;
    protected $table = 'tm_employee';
    protected $guarded = [

    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function divisi()
    {
        return $this->hasOne(Divisi::class, 'divisi_id', 'divisi_id');
    }

    public function location()
    {
        return $this->hasOne(Location::class, 'location_id', 'location_id');
    }
}
