<?php

namespace App\Models\Part;

use App\Models\Master\Customer;
use App\Models\Part\BillProcessItem;
use App\Models\Part\PartCustomer;
use App\Models\Master\Plant;
use App\Models\Master\Proccess;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BillProcess extends Model
{
    protected $table = 'tm_bill_process';
    protected $primaryKey = 'bill_process_id';
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

    public function plant()
    {
        return $this->hasOne(Plant::class, 'plant_id', 'plant_id');
    }

    public function bop_item()
    {
        return $this->hasMany(BillProcessItem::class, 'bill_process_id', 'bill_process_id')->where('is_active', 1);
    }
}
