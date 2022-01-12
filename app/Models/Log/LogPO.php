<?php

namespace App\Models\Log;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Master\StatusProcess;

class LogPO extends Model
{
    use HasFactory;
    protected $table = 'tl_po';
    protected $primaryKey = 'log_id';
    public $incrementing = false;
    protected $guarded = [

    ];

    public function log_status()
    {
        return $this->hasOne(StatusProcess::class, 'status_process_id', 'status_process')->where('is_active', 1);
    }
}
