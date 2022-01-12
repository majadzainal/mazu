<?php

namespace App\Models\Part;

use Carbon\Carbon;
use App\Models\Part\Stock;
use App\Models\Master\Unit;
use App\Models\Master\Plant;
use App\Models\Master\Divisi;
use App\Models\Part\PartPrice;
use App\Models\Master\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PartCustomer extends Model
{
    protected $table = 'tm_part_customer';
    protected $primaryKey = 'part_customer_id';
    public $incrementing = false;
    public $keyType = 'string';
    protected $guarded = [

    ];

    public function customer()
    {
        return $this->hasOne(Customer::class, 'customer_id', 'customer_id');
    }

    public function plant()
    {
        return $this->hasOne(Plant::class, 'plant_id', 'plant_id');
    }

    public function divisi()
    {
        return $this->hasOne(Divisi::class, 'divisi_id', 'divisi_id');
    }

    public function unit()
    {
        return $this->hasOne(Unit::class, 'unit_id', 'unit_id');
    }

    public function part_price()
    {
        return $this->hasMany(PartPrice::class, 'part_customer_id', 'part_customer_id')->where('is_active', 1)->orderBy('price_id', 'ASC');
    }

    public function part_price_active()
    {
        return $this->hasOne(PartPrice::class, 'part_customer_id', 'part_customer_id')
                    ->where('is_active', 1)
                    ->whereDate('effective_date', '<=', Carbon::now())
                    ->orderBy('effective_date', 'DESC');
    }

    public function bom()
    {
        return $this->hasOne(BillMaterial::class, 'part_customer_id', 'part_customer_id');
    }

    public function bop()
    {
        return $this->hasOne(BillProcess::class, 'part_customer_id', 'part_customer_id');
    }

    public function stock_warehouse()
    {
        return $this->hasMany(Stock::class, 'part_customer_id', 'part_customer_id');
    }
}
