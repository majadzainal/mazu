<?php

namespace App\Http\Controllers\MazuMaster;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MazuLog\LogStockExclusiveReseller;
use App\Models\MazuMaster\ExclusiveReseller;
use App\Models\MazuMaster\StockExclusiveReseller;
use RealRashid\SweetAlert\Facades\Alert;

class InventoryExclusiveResellerController extends Controller
{
    public $MenuID = '00402';

    public function listInventory(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        return view('mazumaster.inventoryExcResellerTable', [
            'MenuID' => $this->MenuID,
        ]);
    }
    public function loadExcReseller()
    {
        $excResellerList = ExclusiveReseller::where('store_id', getStoreId())
                    ->with('warehouse')
                    ->where('is_active', 1)->orderBy('created_at', 'DESC')->get();

        return['data'=> $excResellerList];
    }
    public function loadInventory($exc_reseller_id){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $store_id = getStoreId();
        $stockList = StockExclusiveReseller::where('exc_reseller_id', $exc_reseller_id)
                ->where('store_id', $store_id)
                ->with('warehouse', 'product', 'product.unit')->get();

        // dd($stockList);
        return['data'=> $stockList];
    }

    public function loadLogStock($exc_reseller_id, $product_id)
    {
        $logStock = LogStockExclusiveReseller::where('exc_reseller_id', $exc_reseller_id)
                    ->where('product_id', $product_id)
                    ->with('product', 'warehouse')->get();

        return['data'=> $logStock];
    }
    public function loadLogStockSupplier($exc_reseller_id, $product_supplier_id)
    {
        $logStock = LogStockExclusiveReseller::where('exc_reseller_id', $exc_reseller_id)
                    ->where('product_supplier_id', $product_supplier_id)
                    ->with('productSupplier', 'warehouse')->get();

        return['data'=> $logStock];
    }
}
