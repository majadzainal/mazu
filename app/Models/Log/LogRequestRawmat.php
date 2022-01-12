<?php

namespace App\Models\Log;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogRequestRawmat extends Model
{
    use HasFactory;
    protected $table = 'tl_request_rawmat';
    protected $primaryKey = 'log_req_id';
    public $incrementing = false;
    protected $guarded = [

    ];
}
