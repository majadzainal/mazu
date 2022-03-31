<?php

namespace App\Models\MazuProcess;

use App\Models\MazuMaster\Product;
use App\Models\MazuMaster\Warehouse;
use App\Models\MazuProcess\StockOpname;
use Illuminate\Database\Eloquent\Model;
use App\Models\MazuMaster\ProductSupplier;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockOpnameItem extends Model
{
    use HasFactory;
    protected $table = 'tp_stock_opname_item';
    protected $primaryKey = 'stock_opname_item_id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [

    ];

    public function stokOpname()
    {
        return $this->hasOne(StockOpname::class, 'stock_opname_id', 'stock_opname_id');
    }
    public function product()
    {
        return $this->hasOne(Product::class, 'product_id', 'product_id');
    }
    public function productSupplier()
    {
        return $this->hasOne(ProductSupplier::class, 'product_supplier_id', 'product_supplier_id');
    }
    public function warehouse()
    {
        return $this->hasOne(Warehouse::class, 'warehouse_id', 'warehouse_id');
    }
}
