<?php

namespace App\Models\Process;

use App\Models\Master\Plant;
use App\Models\Master\Divisi;
use App\Models\Part\PartCustomer;
use App\Models\Process\SalesOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesOrderItem extends Model
{
    use HasFactory;
    protected $primaryKey = 'so_item_id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'tp_sales_order_item';
    protected $guarded = [

    ];

    public function salesOrder()
    {
        return $this->hasOne(SalesOrder::class, 'sales_order_id', 'sales_order_id');
    }

    public function partCustomer()
    {
        return $this->hasOne(PartCustomer::class, 'part_customer_id', 'part_customer_id');
    }

    public function plant()
    {
        return $this->hasOne(Plant::class, 'plant_id', 'plant_id');
    }

    public function divisi()
    {
        return $this->hasOne(Divisi::class, 'divisi_id', 'divisi_id');
    }
}
