<?php

namespace App\Http\Controllers\Process;

use Throwable;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Models\Part\PartCustomer;
use App\Models\Part\PartSupplier;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class InventoryController extends Controller
{
    public $menuPartSupplier = '0801';
    public $menuPartCustomer = '0802';
    public $menuPartWIP = '0803';

    //INVENTORY
    //INVENTORY PART SUPPLIER
    public function listInventoryPartSupplier(){

        if(!isAccess('read', $this->menuPartSupplier)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->menuPartSupplier,
            ]);
        }

        return view('process.inventoryPartSupplierTable', [
            'MenuID' => $this->menuPartSupplier,
        ]);
    }

    public function loadInventoryPartSupplier(){
        if(!isAccess('read', $this->menuPartSupplier)){
            return['data'=> ''];
        }

        $partList = PartSupplier::where('is_active', 1)->with('supplier', 'divisi', 'part_price_active', 'unit')->get();
        return['data'=> $partList];
    }


    //INVENTORY
    //INVENTORY PART CUSTOMER
    public function listInventoryPartCustomer(){

        if(!isAccess('read', $this->menuPartCustomer)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->menuPartCustomer,
            ]);
        }

        return view('process.inventoryPartCustomerTable', [
            'MenuID' => $this->menuPartCustomer,
        ]);
    }

    public function loadInventoryPartCustomer(){
        if(!isAccess('read', $this->menuPartCustomer)){
            return['data'=> ''];
        }

        $partList = PartCustomer::where('is_active', 1)
                ->where('is_supplier', 0)
                ->with('customer', 'divisi', 'part_price_active', 'unit')->get();

        return['data'=> $partList];
    }

    //INVENTORY
    //INVENTORY PART WIP
    public function listInventoryPartWIP(){

        if(!isAccess('read', $this->menuPartWIP)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->menuPartWIP,
            ]);
        }

        return view('process.inventoryPartWIPTable', [
            'MenuID' => $this->menuPartWIP,
        ]);
    }

    public function loadInventoryPartWIP(){
        if(!isAccess('read', $this->menuPartWIP)){
            return['data'=> ''];
        }

        $partList = PartCustomer::where('is_active', 1)
                ->where('is_supplier', 1)
                ->with('customer', 'divisi', 'part_price_active', 'unit')->get();

        return['data'=> $partList];
    }



    public function loadRawMaterialPartSupplier(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $partList = PartSupplier::where('is_active', 1)->with('supplier', 'divisi', 'part_price_active', 'unit')->get();
        return['data'=> $partList];
    }

}
