<?php

namespace App\Models\MazuMaster;

use App\Models\MazuMaster\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LabelProduct extends Model
{
    protected $table = 'tm_label_product';
    protected $primaryKey = 'label_product_id';
    public $incrementing = false;
    protected $guarded = [

    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
