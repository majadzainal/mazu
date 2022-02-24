<?php

namespace App\Models\MazuMaster;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BroadcastEmail extends Model
{
    protected $table = 'tm_broadcast_email';
    protected $primaryKey = 'broadcast_email_id';
    public $incrementing = false;
    protected $guarded = [

    ];
}
