<?php

namespace App\Models\Process;

use App\Models\Master\Plant;
use App\Models\Process\OpnameSchedule;
use App\Models\Process\StockOpnameItem;
use Illuminate\Database\Eloquent\Model;
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
        return $this->hasOne(OpnameSchedule::class, 'opname_schedule_id', 'opname_schedule_id');
    }

    public function plant()
    {
        return $this->hasOne(Plant::class, 'plant_id', 'plant_id');
    }
}
