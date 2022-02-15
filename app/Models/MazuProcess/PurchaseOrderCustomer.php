<?php

namespace App\Models\MazuProcess;

use App\Models\MazuMaster\Customer;
use Illuminate\Database\Eloquent\Model;
use App\Models\MazuProcess\PurchaseOrderCustomerItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrderCustomer extends Model
{
    use HasFactory;
    protected $table = 'tp_po_customer';
    protected $primaryKey = 'po_customer_id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [

    ];

    public function customer()
    {
        return $this->hasOne(Customer::class, 'customer_id', 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderCustomerItem::class, 'po_customer_id', 'po_customer_id')->orderBy('order_item', 'ASC');
    }
}
