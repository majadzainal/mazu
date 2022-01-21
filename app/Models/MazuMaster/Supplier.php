<?php

namespace App\Models\MazuMaster;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'tm_supplier';
    protected $primaryKey = 'supplier_id';
    public $incrementing = false;
    protected $guarded = [

    ];
}
