<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Master\Plant;
use App\Models\Production\DailyReportItem;
//use App\Models\Log\LogRequestRawmat;

class DailyReport extends Model
{
    use HasFactory;
    protected $table = 'tp_daily_report';
    protected $primaryKey = 'report_id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [

    ];

    public function plant()
    {
        return $this->hasOne(Plant::class, 'plant_id', 'plant_id');
    }

    public function report_item()
    {
        return $this->hasMany(DailyReportItem::class, 'report_id', 'report_id');
    }

    /*public function log_request()
    {
        return $this->hasMany(LogRequestRawmat::class, 'request_id', 'request_id');
    }*/
}
