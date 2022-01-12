<?php

namespace App\Models\Part;

use App\Models\Master\Customer;
use App\Models\Part\PartCustomer;
use App\Models\Part\PartSupplier;
use App\Models\Master\Unit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BillMaterial extends Model
{
    protected $table = 'tm_bill_material';
    protected $primaryKey = 'bill_material_id';
    public $incrementing = false;
    public $keyType = 'string';
    protected $guarded = [

    ];

    public function customer()
    {
        return $this->hasOne(Customer::class, 'customer_id', 'customer_id');
    }

    public function part_customer()
    {
        return $this->hasOne(PartCustomer::class, 'part_customer_id', 'part_customer_id');
    }

    public function bom_item()
    {
        return $this->hasMany(BillMaterialItem::class, 'bill_material_id', 'bill_material_id')->where('is_active', 1);
    }
    

}
