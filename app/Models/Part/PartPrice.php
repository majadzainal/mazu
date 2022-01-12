<?php

namespace App\Models\Part;

use App\Models\Part\PartSupplier;
use App\Models\Part\PartCustomer;
use Illuminate\Database\Eloquent\Model;

class PartPrice extends Model
{
    protected $primaryKey = 'price_id';
    public $incrementing = false;
    protected $table = 'tm_part_price';
    protected $guarded = [

    ];


    public function partSupplier()
    {
        return $this->belongsTo(PartSupplier::class, 'part_supplier_id', 'part_supplier_id');
    }

    public function partCustomer()
    {
        return $this->belongsTo(PartCustomer::class, 'part_customer_id', 'part_customer_id');
    }
}
