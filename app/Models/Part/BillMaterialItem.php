<?php

namespace App\Models\Part;

use App\Models\Master\Customer;
use App\Models\Part\PartCustomer;
use App\Models\Part\PartSupplier;
use App\Models\Master\Unit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BillMaterialItem extends Model
{
    protected $table = 'tm_bill_material_item';
    protected $primaryKey = 'item_bom_id';
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

    public function unit_bom()
    {
        return $this->hasOne(Unit::class, 'unit_id', 'unit_id');
    }

    public function wip()
    {
        return $this->hasOne(PartCustomer::class, 'part_customer_id', 'part_id')->where('is_supplier', 1);
    }

}
