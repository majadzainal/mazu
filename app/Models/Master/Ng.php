<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ng extends Model
{
    protected $primaryKey = 'ng_id';
    public $incrementing = false;
    protected $table = 'tm_ng';
    protected $guarded = [

    ];

}
