<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Process extends Model
{
    public $incrementing = false;
    protected $table = 'tm_process';
    protected $primaryKey = 'process_id';
    protected $guarded = [

    ];


}
