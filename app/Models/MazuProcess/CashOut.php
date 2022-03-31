<?php

namespace App\Models\MazuProcess;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashOut extends Model
{
    protected $table = 'tp_cash_out';
    protected $primaryKey = 'cash_out_id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [

    ];
}
