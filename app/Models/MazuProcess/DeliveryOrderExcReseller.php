<?php

namespace App\Models\MazuProcess;

use App\Models\MazuMaster\ExclusiveReseller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MazuProcess\DeliveryOrderExcResellerItem;

class DeliveryOrderExcReseller extends Model
{
    use HasFactory;
    protected $table = 'tp_do_exc_reseller';
    protected $primaryKey = 'do_exc_reseller_id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [

    ];

    public function excReseller()
    {
        return $this->hasOne(ExclusiveReseller::class, 'exc_reseller_id', 'exc_reseller_id');
    }

    public function items()
    {
        return $this->hasMany(DeliveryOrderExcResellerItem::class, 'do_exc_reseller_id', 'do_exc_reseller_id')->orderBy('order_item', 'ASC');
    }
}
