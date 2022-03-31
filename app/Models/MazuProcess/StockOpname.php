<?php

namespace App\Models\MazuProcess;

use Illuminate\Database\Eloquent\Model;
use App\Models\MazuProcess\StockOpnameItem;
use App\Models\MazuMaster\StockOpnameSchedule;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockOpname extends Model
{
    use HasFactory;
    protected $table = 'tp_stock_opname';
    protected $primaryKey = 'stock_opname_id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [

    ];

    public function opname_item()
    {
        return $this->hasMany(StockOpnameItem::class, 'stock_opname_id', 'stock_opname_id')->orderBy('order_item', 'ASC');
    }

    public function schedule()
    {
        return $this->hasOne(StockOpnameSchedule::class, 'stock_opname_schedule_id', 'stock_opname_schedule_id');
    }
}
