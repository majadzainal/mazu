<?php

namespace App\Models\MazuProcess;

use App\Models\MazuMaster\PaidType;
use App\Models\MazuProcess\SalesOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesOrderPaid extends Model
{
    protected $table = 'tp_sales_order_paid';
    protected $primaryKey = 'sales_order_paid_id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [

    ];


    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'so_id', 'so_id');
    }

    public function paidType()
    {
        return $this->belongsTo(PaidType::class, 'paid_type_id', 'paid_type_id');
    }
}
