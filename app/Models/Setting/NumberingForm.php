<?php

namespace App\Models\Setting;

use App\Models\Setting\Counter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NumberingForm extends Model
{
    use HasFactory;
    protected $table = 'ts_numbering_form';
    protected $primaryKey = 'numbering_form_id';
    protected $guarded = ['numbering_form_id','numbering_form_type'];

    public function counter()
    {
        return $this->hasOne(Counter::class, 'counter_id', 'counter_id');
    }
}
