<?php

namespace App\Http\Controllers\MazuMaster;

use Illuminate\Http\Request;
use App\Models\MazuMaster\Outlet;
use App\Http\Controllers\Controller;
use App\Models\MazuLog\LogStockOutlet;
use App\Models\MazuMaster\StockOutlet;
use RealRashid\SweetAlert\Facades\Alert;

class InventoryOutletController extends Controller
{
    public $MenuID = '00302';

    public function listInventory(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        return view('mazumaster.inventoryOutletTable', [
            'MenuID' => $this->MenuID,
        ]);
    }
    public function loadOutlet()
    {
        $outletList = Outlet::where('store_id', getStoreId())
                    ->with('warehouse')
                    ->where('is_active', 1)->orderBy('created_at', 'DESC')->get();

        return['data'=> $outletList];
    }
    public function loadInventory($outlet_id){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $store_id = getStoreId();
        $stockList = StockOutlet::where('outlet_id', $outlet_id)
                ->where('store_id', $store_id)
                ->with('warehouse', 'product', 'product.unit', 'productSupplier', 'productSupplier.unit')->get();

        // dd($stockList);
        return['data'=> $stockList];
    }

    public function loadLogStock($outlet_id, $product_id)
    {
        $logStock = LogStockOutlet::where('outlet_id', $outlet_id)
                    ->where('product_id', $product_id)
                    ->with('product', 'warehouse')->get();

        return['data'=> $logStock];
    }

    public function loadLogStockSupplier($outlet_id, $product_supplier_id)
    {
        $logStock = LogStockOutlet::where('outlet_id', $outlet_id)
                    ->where('product_supplier_id', $product_supplier_id)
                    ->with('productSupplier', 'warehouse')->get();

        return['data'=> $logStock];
    }
}
