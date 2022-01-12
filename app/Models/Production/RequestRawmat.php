<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Master\Plant;
use App\Models\Production\RequestRawmatItem;
use App\Models\Log\LogRequestRawmat;

class RequestRawmat extends Model
{
    use HasFactory;
    protected $table = 'tp_request_rawmat';
    protected $primaryKey = 'request_id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [

    ];

    public function plant()
    {
        return $this->hasOne(Plant::class, 'plant_id', 'plant_id');
    }

    public function request_item()
    {
        return $this->hasMany(RequestRawmatItem::class, 'request_id', 'request_id');
    }

    public function log_request()
    {
        return $this->hasMany(LogRequestRawmat::class, 'request_id', 'request_id');
    }
}
