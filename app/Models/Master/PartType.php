<?php

namespace App\Models\Master;

use App\Models\Master\Divisi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PartType extends Model
{
    protected $primaryKey = 'part_type_id';
    public $incrementing = false;
    protected $table = 'tm_part_type';
    protected $guarded = [];

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'part_type_id', 'part_type_id');
    }
}
