<?php

namespace App\Models\Process;

use App\Models\Master\Customer;
use App\Models\Process\SalesOrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesOrder extends Model
{
    use HasFactory;
    protected $table = 'tp_sales_order';
    protected $primaryKey = 'sales_order_id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [

    ];

    public function customer()
    {
        return $this->hasOne(Customer::class, 'customer_id', 'customer_id');
    }

    public function so_items()
    {
        return $this->hasMany(SalesOrderItem::class, 'sales_order_id', 'sales_order_id')->orderBy('order_item', 'ASC');
    }
}
