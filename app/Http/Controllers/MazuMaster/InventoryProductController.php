<?php

namespace App\Http\Controllers\MazuMaster;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MazuLog\LogStock;
use App\Models\MazuMaster\Product;
use RealRashid\SweetAlert\Facades\Alert;

class InventoryProductController extends Controller
{
    public $MenuID = '01102';

    public function listInventory(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        return view('mazumaster.inventoryProductTable', [
            'MenuID' => $this->MenuID,
        ]);
    }
    public function loadProduct()
    {
        $productList = Product::where('store_id', getStoreId())
                    ->with('stockWarehouse', 'stockWarehouse.warehouse')
                    ->where('is_active', 1)->orderBy('created_at', 'DESC')->get();

        return['data'=> $productList];
    }

    public function loadLogStock($product_id)
    {
        $logStock = LogStock::where('product_id', $product_id)
                    ->with('product', 'warehouse')->get();

        return['data'=> $logStock];
    }
}
