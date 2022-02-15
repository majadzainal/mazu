<?php

namespace App\Models\MazuMaster;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Endorse extends Model
{
    protected $table = 'tm_endorse';
    protected $primaryKey = 'endorse_id';
    public $incrementing = false;
    protected $guarded = [

    ];
}
