<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DayOff extends Model
{
    use HasFactory;
    protected $primaryKey = 'day_off_id';
    public $incrementing = false;
    protected $table = 'tm_day_off';
    protected $guarded = [

    ];
}
