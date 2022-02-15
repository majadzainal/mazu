<?php

namespace App\Models\MazuProcess;

use App\Models\MazuMaster\Outlet;
use Illuminate\Database\Eloquent\Model;
use App\Models\MazuProcess\DeliveryOrderOutletItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeliveryOrderOutlet extends Model
{
    use HasFactory;
    protected $table = 'tp_do_outlet';
    protected $primaryKey = 'do_outlet_id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [

    ];

    public function outlet()
    {
        return $this->hasOne(Outlet::class, 'outlet_id', 'outlet_id');
    }

    public function items()
    {
        return $this->hasMany(DeliveryOrderOutletItem::class, 'do_outlet_id', 'do_outlet_id')->orderBy('order_item', 'ASC');
    }
}
