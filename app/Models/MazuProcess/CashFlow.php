<?php

namespace App\Models\MazuProcess;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashFlow extends Model
{
    protected $table = 'tp_cash_flow';
    protected $primaryKey = 'cash_flow_id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [

    ];
}
