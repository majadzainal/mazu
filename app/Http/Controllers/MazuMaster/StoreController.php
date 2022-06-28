<?php

namespace App\Http\Controllers\MazuMaster;

use Throwable;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Models\MazuMaster\Store;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    public function addStore(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            $store_id = Uuid::uuid4()->toString();
            Store::create([
                'store_id'                      => $store_id,
                'store_name'                    => $request->store_name,
                'description'                   => $request->description,
                'store_telephone'               => $request->store_telephone,
                'store_fax'                     => $request->store_fax,
                'store_email'                   => $request->store_email,
                'store_address'                 => $request->store_address,
                'npwp'                          => $request->npwp,
                'logo'                          => "mazulabel.jpg",
                'is_event'                      => $request->is_event,
                'is_active'                     => 1,
            ]);

            return response()->json(['status' => 'Success', 'message' => 'Add Store success.'], 200);

        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function updateStore(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            $store = Store::find($request->store_id);
            if($store){
                $store->update([
                    'store_name'                    => $request->store_name,
                    'description'                   => $request->description,
                    'store_telephone'               => $request->store_telephone,
                    'store_fax'                     => $request->store_fax,
                    'store_email'                   => $request->store_email,
                    'store_address'                 => $request->store_address,
                    'npwp'                          => $request->npwp,
                    'is_event'                      => $request->is_event,
                ]);

                return response()->json(['status' => 'Success', 'message' => 'Edit store success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'Store not found.'], 200);
            }
        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function deleteStore($id)
    {
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            $store = Store::find($id);
            if ($store){
                $store->is_active = 0;
                $store->update();

                return response()->json(['status' => 'Success', 'message' => 'Delete store success.'], 200);
            } else {
                return response()->json(['status' => 'info', 'message' => 'Delete store failed.'], 202);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }
    }
}
