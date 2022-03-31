<?php

namespace App\Models\MazuMaster;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOpnameSchedule extends Model
{
    protected $table = 'tm_stock_opname_schedule';
    protected $primaryKey = 'stock_opname_schedule_id';
    public $incrementing = false;
    protected $guarded = [

    ];
}
