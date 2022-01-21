<?php

namespace App\Http\Controllers\MazuProcess;

use Illuminate\Http\Request;
use App\Models\MazuMaster\Product;
use App\Models\MazuMaster\Supplier;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\MazuProcess\PurchaseOrderSupplier;

class PurchaseOrderSupplierController extends Controller
{
    public $MenuID = '003';
    public $objNumberingForm;
    public $generateType = 'F_PURCHASE_ORDER_SUPPLIER';

    public function listPOSupplier(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        $store_id = getStoreId();
        $supplierList = Supplier::where('store_id', $store_id)->where('is_active', 1)->get();
        $productList = Product::where('store_id', $store_id)->where('is_active', 1)->with('unit', 'stockWarehouse', 'stockWarehouse.warehouse')->get();

        return view('mazuprocess.poSupplierTable', [
            'MenuID'            => $this->MenuID,
            'supplierList'      => $supplierList,
            'productList'       => $productList,
        ]);
    }

    public function loadProduct(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $productList = Product::where('store_id', getStoreId())->where('is_active', 1)
                    ->with('category', 'unit', 'stockWarehouse', 'stockWarehouse.warehouse')
                    ->orderBy('created_at', 'DESC')->get();

        return['data'=> $productList];
    }

    public function loadPOSupplier($start_date, $end_date){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $store_id = getStoreId();
        $poSupplierList = PurchaseOrderSupplier::where('is_active', 1)
                                        ->whereBetween('po_date', [$start_date, $end_date])
                                        ->where('store_id', $store_id)
                                        // ->with('supplier')
                                        ->orderBy('po_date', 'desc')
                                        ->get();

        return['data'=> $poSupplierList];
    }
}
