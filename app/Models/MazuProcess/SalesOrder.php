<?php

namespace App\Models\MazuProcess;

use App\Models\MazuMaster\Owner;
use App\Models\MazuMaster\Outlet;
use App\Models\MazuMaster\Endorse;
use App\Models\MazuMaster\Customer;
use Illuminate\Database\Eloquent\Model;
use App\Models\MazuProcess\SalesOrderItem;
use App\Models\MazuProcess\SalesOrderPaid;
use App\Models\MazuMaster\ExclusiveReseller;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesOrder extends Model
{
    use HasFactory;
    protected $table = 'tp_sales_order';
    protected $primaryKey = 'so_id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [

    ];

    public function items()
    {
        return $this->hasMany(SalesOrderItem::class, 'so_id', 'so_id')->orderBy('order_item', 'ASC');
    }

    public function paid()
    {
        return $this->hasMany(SalesOrderPaid::class, 'so_id', 'so_id');
    }

    public function endorse()
    {
        return $this->hasOne(Endorse::class, 'endorse_id', 'endorse_id');
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'customer_id', 'customer_id');
    }

    public function outlet()
    {
        return $this->hasOne(Outlet::class, 'outlet_id', 'outlet_id');
    }

    public function excReseller()
    {
        return $this->hasOne(ExclusiveReseller::class, 'exc_reseller_id', 'exc_reseller_id');
    }

    public function owner()
    {
        return $this->hasOne(Owner::class, 'owner_id', 'owner_id');
    }

    public function poCust()
    {
        return $this->hasOne(PurchaseOrderCustomer::class, 'po_customer_id', 'po_customer_id');
    }


}
