<?php

namespace App\Models\Part;

use App\Models\Master\Supplier;
use App\Models\Master\Divisi;
use App\Models\Master\Unit;
use App\Models\Part\PartPrice;
use App\Models\Part\Stock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class PartSupplier extends Model
{
    protected $table = 'tm_part_supplier';
    protected $primaryKey = 'part_supplier_id';
    public $incrementing = false;
    public $keyType = 'string';
    protected $guarded = [

    ];

    public function supplier()
    {
        return $this->hasOne(Supplier::class, 'supplier_id', 'supplier_id');
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
        return $this->hasMany(PartPrice::class, 'part_supplier_id', 'part_supplier_id')->where('is_active', 1)->orderBy('price_id', 'ASC');
    }

    public function part_price_active()
    {
        return $this->hasOne(PartPrice::class, 'part_supplier_id', 'part_supplier_id')
                    ->where('is_active', 1)
                    ->whereDate('effective_date', '<=', Carbon::now())
                    ->orderBy('effective_date', 'DESC');
    }

    public function stock_warehouse()
    {
        return $this->hasMany(Stock::class, 'part_supplier_id', 'part_supplier_id');
    }

}
