<?php

namespace App\Models\MazuProcess;

use App\Models\MazuProcess\CashOut;
use App\Models\MazuProcess\Production;
use Illuminate\Database\Eloquent\Model;
use App\Models\MazuProcess\SalesOrderPaid;
use App\Models\MazuProcess\PurchaseOrderCustomer;
use App\Models\MazuProcess\PurchaseOrderMaterial;
use App\Models\MazuProcess\PurchaseOrderSupplier;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GeneralLedger extends Model
{
    protected $table = 'tp_general_ledger';
    protected $primaryKey = 'general_ledger_id';
    public $incrementing = false;
    protected $guarded = [

    ];

    public function poCustomer()
    {
        return $this->hasOne(PurchaseOrderCustomer::class, 'po_customer_id', 'po_customer_id');
    }

    public function poSupplier()
    {
        return $this->hasOne(PurchaseOrderSupplier::class, 'po_supplier_id', 'po_supplier_id');
    }

    public function production()
    {
        return $this->hasOne(Production::class, 'production_id', 'production_id');
    }

    public function poMaterial()
    {
        return $this->hasOne(PurchaseOrderMaterial::class, 'po_material_id', 'po_material_id');
    }

    public function soPaid()
    {
        return $this->hasOne(SalesOrderPaid::class, 'sales_order_paid_id', 'sales_order_paid_id');
    }

    public function cashOut()
    {
        return $this->hasOne(CashOut::class, 'cash_out_id', 'cash_out_id');
    }
}
