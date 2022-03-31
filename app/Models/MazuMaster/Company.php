<?php

namespace App\Models\MazuMaster;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'tm_company';
    protected $primaryKey = 'company_id';
    public $incrementing = false;
    protected $guarded = [

    ];
}
