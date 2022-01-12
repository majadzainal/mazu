<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Part\PartSupplier;
use App\Models\Part\PartCustomer;
use App\Models\Master\Warehouse;
use App\Models\Master\Unit;

class RequestRawmatItem extends Model
{
    protected $table = 'tp_request_rawmat_item';
    protected $primaryKey = 'request_item_id';
    public $incrementing = false;
    protected $guarded = [

    ];

    public function part_supplier()
    {
        return $this->hasOne(PartSupplier::class, 'part_supplier_id', 'part_id');
    }

    public function part_customer()
    {
        return $this->hasOne(PartCustomer::class, 'part_customer_id', 'part_id');
    }
    
    public function warehouse()
    {
        return $this->hasOne(Warehouse::class, 'warehouse_id', 'warehouse_id');
    }

    public function units()
    {
        return $this->hasOne(Unit::class, 'unit_id', 'unit');
    }

}
