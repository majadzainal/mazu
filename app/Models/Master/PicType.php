<?php

namespace App\Models\Master;

use App\Models\Master\PIC;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PicType extends Model
{
    protected $primaryKey = 'pic_type_id';
    public $incrementing = false;
    protected $table = 'tm_pic_type';
    protected $fillable = ['pic_type_id','pic_type_name','is_active'];

    public function pic()
    {
        return $this->belongsTo(PIC::class, 'pic_type_id', 'pic_type_id');
    }
}
