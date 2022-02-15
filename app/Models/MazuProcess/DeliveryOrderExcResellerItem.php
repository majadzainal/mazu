<?php

namespace App\Models\MazuProcess;

use App\Models\MazuMaster\Product;
use App\Models\MazuMaster\Warehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeliveryOrderExcResellerItem extends Model
{
    protected $primaryKey = 'do_exc_reseller_item_id';
    public $incrementing = false;
    protected $table = 'tp_do_exc_reseller_item';
    protected $guarded = [

    ];


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'warehouse_id');
    }
}
