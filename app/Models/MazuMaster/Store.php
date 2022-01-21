<?php

namespace App\Models\MazuMaster;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $table = 'tm_store';
    protected $primaryKey = 'store_id';
    public $incrementing = false;
    protected $guarded = [

    ];
}
