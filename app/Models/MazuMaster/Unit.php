<?php

namespace App\Models\MazuMaster;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'tm_unit';
    protected $primaryKey = 'unit_id';
    public $incrementing = false;
    protected $guarded = [

    ];
}
