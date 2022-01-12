<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Part\PartCustomer;

class DailyReportItem extends Model
{
    protected $table = 'tp_daily_report_item';
    protected $primaryKey = 'report_item_id';
    public $incrementing = false;
    protected $guarded = [

    ];

    public function part_customer()
    {
        return $this->hasOne(PartCustomer::class, 'part_customer_id', 'part_customer_id');
    }
    
}
