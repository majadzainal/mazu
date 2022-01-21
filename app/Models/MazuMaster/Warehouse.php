<?php

namespace App\Models\MazuMaster;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $table = 'tm_warehouse';
    protected $primaryKey = 'warehouse_id';
    public $incrementing = false;
    protected $guarded = [

    ];
}
