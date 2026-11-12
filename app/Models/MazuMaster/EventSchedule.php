<?php

namespace App\Models\MazuMaster;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSchedule extends Model
{
    protected $table = 'tm_event_schedule';
    protected $primaryKey = 'event_schedule_id';
    public $incrementing = false;
    protected $guarded = [

    ];
}
