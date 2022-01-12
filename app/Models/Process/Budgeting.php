<?php

namespace App\Models\Process;

use App\Models\Part\PartSupplier;
use App\Models\Process\SalesOrder;
use App\Models\Process\Forecast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Budgeting extends Model
{
    
    use HasFactory;
    protected $table = 'tp_budgeting';
    protected $primaryKey = 'budgeting_id';
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

    public function forecast()
    {
        return $this->hasMany(Forecast::class, 'part_supplier_id', 'part_supplier_id');
    }
}
