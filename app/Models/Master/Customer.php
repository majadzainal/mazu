<?php

namespace App\Models\Master;

use App\Models\Master\PIC;
use App\Models\Master\Divisi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    protected $table = 'tm_customer';
    protected $primaryKey = 'customer_id';
    public $incrementing = false;
    protected $guarded = [

    ];

    public function divisi()
    {
        return $this->hasOne(Divisi::class, 'divisi_id', 'divisi_id');
    }

    public function persons()
    {
        return $this->hasMany(PIC::class, 'customer_id', 'customer_id');
    }
}
