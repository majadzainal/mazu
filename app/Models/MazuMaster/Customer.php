<?php

namespace App\Models\MazuMaster;

use Illuminate\Database\Eloquent\Model;
use App\Models\MazuMaster\CustomerCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    protected $table = 'tm_customer';
    protected $primaryKey = 'customer_id';
    public $incrementing = false;
    protected $guarded = [

    ];

    public function category()
    {
        return $this->hasOne(CustomerCategory::class, 'customer_category_id', 'customer_category_id');
    }
}
