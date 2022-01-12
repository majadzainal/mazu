<?php

namespace App\Models\Part;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Master\Warehouse;

class Stock extends Model
{
    protected $table = 'tm_stock';
    protected $primaryKey = 'stock_id';
    public $incrementing = false;
    public $keyType = 'string';
    protected $guarded = [

    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'warehouse_id');
    }

    public function partCustomer()
    {
        return $this->belongsTo(PartCustomer::class, 'part_customer_id', 'part_customer_id')->where('is_supplier', 0);
    }
}
