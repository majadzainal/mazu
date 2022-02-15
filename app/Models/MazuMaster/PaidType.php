<?php

namespace App\Models\MazuMaster;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaidType extends Model
{
    use HasFactory;protected $table = 'tm_paid_type';
    protected $primaryKey = 'paid_type_id';
    public $incrementing = false;
    public $keyType = 'string';
    protected $guarded = [

    ];
}
