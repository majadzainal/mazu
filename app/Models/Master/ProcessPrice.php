<?php

namespace App\Models\Master;

use App\Models\Master\Process;
use App\Models\Master\ProcessMachine;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProcessPrice extends Model
{
    use HasFactory;
    protected $table = 'tm_process_price';
    protected $primaryKey = 'process_price_id';
    protected $guarded = [

    ];

    public function process()
    {
        return $this->hasOne(Process::class, 'process_id', 'process_id');
    }

    public function processMachine()
    {
        return $this->hasOne(ProcessMachine::class, 'pmachine_id', 'pmachine_id');
    }
}
