<?php

namespace App\Models\Part;

use App\Models\Master\Plant;
use App\Models\Master\Process;
use App\Models\Master\Customer;
use App\Models\Part\PartCustomer;
use App\Models\Master\ProcessPrice;
use App\Models\Master\ProcessMachine;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BillProcessItem extends Model
{
    protected $table = 'tm_bill_process_item';
    protected $primaryKey = 'item_bop_id';
    public $incrementing = false;
    protected $guarded = [

    ];

    public function process()
    {
        return $this->hasOne(Process::class, 'process_id', 'process_id');
    }

    public function machine()
    {
        return $this->hasOne(ProcessMachine::class, 'pmachine_id', 'mc');
    }

    public function process_price()
    {
        return $this->hasOne(ProcessPrice::class, 'process_id', 'process_id')
                    ->where('effective_date', '<=', date("Y-m-d"))
                    ->orderBy('effective_date', 'DESC');
    }
}
