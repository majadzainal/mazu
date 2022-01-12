<?php

namespace App\Models\Master;

use App\Models\Master\PicType;
use App\Models\Master\Customer;
use App\Models\Master\Supplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PIC extends Model
{
    public $incrementing = false;
    protected $table = 'tm_pic';
    protected $primaryKey = 'pic_id';
    protected $guarded = [

    ];

    public function supplier()
    {
        return $this->hasOne(Supplier::class, 'supplier_id', 'supplier_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function pic_type()
    {
        return $this->hasOne(PicType::class, 'pic_type_id', 'pic_type_id');
    }
}
