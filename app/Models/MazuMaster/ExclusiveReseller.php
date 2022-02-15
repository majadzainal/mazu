<?php

namespace App\Models\MazuMaster;

use App\Models\MazuMaster\Warehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExclusiveReseller extends Model
{
    protected $table = 'tm_exc_reseller';
    protected $primaryKey = 'exc_reseller_id';
    public $incrementing = false;
    protected $guarded = [

    ];

    public function warehouse()
    {
        return $this->hasOne(Warehouse::class, 'warehouse_id', 'warehouse_id');
    }
}
