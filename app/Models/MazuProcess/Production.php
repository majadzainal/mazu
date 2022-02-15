<?php

namespace App\Models\MazuProcess;

use App\Models\MazuMaster\Supplier;
use Illuminate\Database\Eloquent\Model;
use App\Models\MazuProcess\ProductionItem;
use App\Models\MazuProcess\PurchaseOrderCustomer;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Production extends Model
{
    use HasFactory;
    protected $table = 'tp_production';
    protected $primaryKey = 'production_id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [

    ];

    public function supplier()
    {
        return $this->hasOne(Supplier::class, 'supplier_id', 'supplier_id');
    }
    public function poCustomer()
    {
        return $this->hasOne(PurchaseOrderCustomer::class, 'po_customer_id', 'po_customer_id');
    }

    public function items()
    {
        return $this->hasMany(ProductionItem::class, 'production_id', 'production_id')->orderBy('order_item', 'ASC');
    }
}
