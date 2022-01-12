<?php

namespace App\Models\Process;

use App\Models\Part\PartSupplier;
use App\Models\Process\SalesOrder;
use App\Models\Process\Budgeting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Forecast extends Model
{
    use HasFactory;
    protected $table = 'tp_forecast';
    protected $primaryKey = 'forecast_id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [

    ];

    public function salesOrder()
    {
        return $this->hasOne(SalesOrder::class, 'sales_order_id', 'sales_order_id');
    }
    public function partSupplier()
    {
        return $this->hasOne(PartSupplier::class, 'part_supplier_id', 'part_supplier_id');
    }

    public function budgeting()
    {
        return $this->belongsTo(Budgeting::class, 'part_supplier_id', 'part_supplier_id');
    }
}
