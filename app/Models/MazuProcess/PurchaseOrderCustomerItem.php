<?php

namespace App\Models\MazuProcess;

use App\Models\MazuMaster\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrderCustomerItem extends Model
{
    protected $primaryKey = 'po_customer_item_id';
    public $incrementing = false;
    protected $table = 'tp_po_customer_item';
    protected $guarded = [

    ];


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
