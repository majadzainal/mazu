<?php

namespace App\Http\Controllers\MazuMaster;

use Illuminate\Http\Request;
use App\Models\MazuLog\LogStock;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\MazuMaster\ProductSupplier;

class InventoryProductSupplierController extends Controller
{
    public $MenuID = '01202';

    public function listInventory(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        return view('mazumaster.inventoryProductSupplierTable', [
            'MenuID' => $this->MenuID,
        ]);
    }
    public function loadProduct()
    {
        $productList = ProductSupplier::where('store_id', getStoreId())
                    ->where('is_service', 0)
                    ->with('stockWarehouse', 'stockWarehouse.warehouse')
                    ->where('is_active', 1)->orderBy('created_at', 'DESC')->get();

        return['data'=> $productList];
    }

    public function loadLogStock($product_supplier_id)
    {
        $logStock = LogStock::where('product_supplier_id', $product_supplier_id)
                    ->with('productSupplier', 'warehouse')->get();

        return['data'=> $logStock];
    }
}
