<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpnameType extends Model
{
    use HasFactory;
    protected $table = 'tm_opname_type';
    protected $primaryKey = 'opname_type_id';
    protected $guarded = [

    ];
}
