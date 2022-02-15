<?php

namespace App\Http\Controllers\MazuProcess;

use Illuminate\Http\Request;
use App\Models\MazuMaster\Product;
use App\Http\Controllers\Controller;
use App\Models\MazuMaster\ProductSupplier;
use RealRashid\SweetAlert\Facades\Alert;

class InventoryController extends Controller
{
    public $menuProduct = '09201';
    public $menuProductSupplier = '09201';

    //INVENTORY
    //INVENTORY PART SUPPLIER
    public function listInventoryProduct(){

        if(!isAccess('read', $this->menuProduct)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->menuProduct,
            ]);
        }

        return view('process.inventoryProductTable', [
            'MenuID' => $this->menuProduct,
        ]);
    }

    public function loadInventoryProduct(){
        if(!isAccess('read', $this->menuProduct)){
            return['data'=> ''];
        }

        $productList = Product::where('is_active', 1)->with('unit')->get();
        return['data'=> $productList];
    }


    //INVENTORY
    //INVENTORY PART CUSTOMER
    public function listInventoryProductSupplier(){

        if(!isAccess('read', $this->menuProductSupplier)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->menuProductSupplier,
            ]);
        }

        return view('process.inventoryProductSupplierTable', [
            'MenuID' => $this->menuProductSupplier,
        ]);
    }

    public function loadInventoryProductSupplier(){
        if(!isAccess('read', $this->menuProductSupplier)){
            return['data'=> ''];
        }

        $productList = ProductSupplier::where('is_active', 1)->with('unit')->get();
        return['data'=> $productList];
    }

    //INVENTORY
}
