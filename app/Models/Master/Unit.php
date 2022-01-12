<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $primaryKey = 'unit_id';
    public $incrementing = false;
    protected $table = 'tm_unit';
    protected $fillable = ['unit_id','unit_name','is_active'];

}
