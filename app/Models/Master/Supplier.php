<?php

namespace App\Models\Master;

use App\Models\Master\PIC;
use App\Models\Master\Divisi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    protected $table = 'tm_supplier';
    protected $primaryKey = 'supplier_id';
    public $incrementing = false;
    protected $guarded = [

    ];

    // public function divisi()
    // {
    //     return $this->hasOne(Divisi::class, 'divisi_id', 'divisi_id');
    // }

    public function pic()
    {
        return $this->hasMany(PIC::class, 'supplier_id', 'supplier_id');
    }
}
