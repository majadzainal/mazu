<?php

namespace App\Http\Controllers\MazuMaster;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MazuMaster\Store;
use RealRashid\SweetAlert\Facades\Alert;

class StoreController extends Controller
{
    public $MenuID = '00201';

    public function listStore(){
        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        return view('mazumaster.storeTable', [
            'MenuID' => $this->MenuID,
        ]);

    }

    public function loadStore(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $storeList = Store::where('is_active', 1)->orderBy('created_at', 'DESC')->get();
        return['data'=> $storeList];
    }
}
