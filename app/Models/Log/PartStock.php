<?php

namespace App\Models\Log;

use App\Models\Master\Warehouse;
use App\Models\Part\PartCustomer;
use App\Models\Part\PartSupplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PartStock extends Model
{
    use HasFactory;
    protected $table = 'tl_part_stock';
    protected $primaryKey = 'part_stock_id';
    public $incrementing = false;
    protected $guarded = [

    ];

    public function part_supplier()
    {
        return $this->hasOne(PartSupplier::class, 'part_supplier_id', 'part_supplier_id');
    }

    public function part_customer()
    {
        return $this->hasOne(PartCustomer::class, 'part_customer_id', 'part_customer_id');
    }

    public function warehouse()
    {
        return $this->hasOne(Warehouse::class, 'warehouse_id', 'warehouse_id');
    }
}
