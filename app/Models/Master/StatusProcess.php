<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusProcess extends Model
{
    use HasFactory;
    protected $table = 'tm_status_process';
    protected $primaryKey = 'status_process_id';
    protected $guarded = [

    ];
}
