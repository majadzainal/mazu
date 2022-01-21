<?php

namespace App\Models\MazuMaster;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $table = 'tm_product_category';
    protected $primaryKey = 'product_category_id';
    public $incrementing = false;
    protected $guarded = [

    ];
}
