<?php

namespace App\Models\Setting;

use App\Models\Setting\NumberingForm;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Counter extends Model
{
    use HasFactory;
    protected $table = 'ts_counter';
    protected $primaryKey = 'counter_id';
    protected $guarded = [

    ];
}
