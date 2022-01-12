<?php

namespace App\Models\Part;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartLabel extends Model
{
    use HasFactory;
    protected $table = 'tm_part_label';
    protected $primaryKey = 'part_label_id';
    public $incrementing = false;

    protected $guarded = [

    ];

    public function part_supplier()
    {
        return $this->hasOne(PartSupplier::class, 'part_supplier_id', 'part_supplier_id');
    }

    public function part_customer()
    {
        return $this->hasOne(PartCustomer::class, 'part_customer_id', 'part_customer_id');
    }

}
