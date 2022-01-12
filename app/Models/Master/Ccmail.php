<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class Ccmail extends Model
{
    protected $primaryKey = 'ccmail_id';
    public $incrementing = false;
    protected $table = 'tm_ccmail';
    protected $guarded = [

    ];

    
}
