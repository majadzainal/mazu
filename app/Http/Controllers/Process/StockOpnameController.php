<?php

namespace App\Http\Controllers\Process;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\Part\StockController;
use App\Http\Controllers\Log\LogPartStockController;
use App\Http\Controllers\Setting\NumberingFormController;
use App\Models\Process\StockOpname;

class StockOpnameController extends Controller
{
    public $MenuID = '10';
    public $objStock;
    public $objLogPartStock;
    public $objNumberingForm;
    public $generateType = 'F_STOCK_OPNAME';

    public function __construct()
    {
        $this->objStock = new StockController();
        $this->objLogPartStock = new LogPartStockController();
        $this->objNumberingForm = new NumberingFormController();
    }

    public function listStockOpname(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        $partCustOpnameList = StockOpname::where('is_part_customer', 1)
                    ->where('is_active', 1)
                    ->with('opname_item', 'opname_item.partCustomer')
                    ->get();

        $partWIPOpnameList = StockOpname::where('is_wip', 1)
                    ->where('is_active', 1)
                    ->with('opname_item', 'opname_item.partCustomer')
                    ->get();

        $partSupplierOpnameList = StockOpname::where('is_part_supplier', 1)
                    ->where('is_active', 1)
                    ->with('opname_item', 'opname_item.partSupplier')
                    ->get();

        return view('process.stockOpnameTable', [
            'MenuID' => $this->MenuID,
            'partCustOpnameList' => $partCustOpnameList,
            'partWIPOpnameList' => $partWIPOpnameList,
            'partSupplierOpnameList' => $partSupplierOpnameList,
        ]);

    }
}
