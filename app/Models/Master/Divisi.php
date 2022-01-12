<?php

namespace App\Models\Master;

use App\Models\Master\Employee;
use App\Models\Master\PartType;
use App\Models\Master\Supplier;
use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    protected $primaryKey = 'divisi_id';
    public $incrementing = false;
    protected $table = 'tm_divisi';
    protected $guarded = [ ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'divisi_id', 'divisi_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'divisi_id', 'divisi_id');
    }

    public function partType()
    {
        return $this->belongsTo(PartType::class, 'part_type_id', 'part_type_id');
    }
}
