<?php

namespace App\Models\MazuMaster;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    protected $table = 'tm_owner';
    protected $primaryKey = 'owner_id';
    public $incrementing = false;
    protected $guarded = [

    ];
}
