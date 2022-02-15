<?php

namespace App\Models\MazuProcess;

use App\Models\MazuMaster\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesOrderItem extends Model
{
    protected $primaryKey = 'so_item_id';
    public $incrementing = false;
    protected $table = 'tp_sales_order_item';
    protected $guarded = [

    ];


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
