<?php

namespace App\Models\Process;

use App\Models\Master\Warehouse;
use App\Models\Part\PartCustomer;
use App\Models\Part\PartSupplier;
use App\Models\Process\StockOpname;
use Illuminate\Database\Eloquent\Model;
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
    public function partSupplier()
    {
        return $this->hasOne(PartSupplier::class, 'part_supplier_id', 'part_supplier_id');
    }
    public function partCustomer()
    {
        return $this->hasOne(PartCustomer::class, 'part_customer_id', 'part_customer_id');
    }
    public function warehouse()
    {
        return $this->hasOne(Warehouse::class, 'warehouse_id', 'warehouse_id');
    }
}
