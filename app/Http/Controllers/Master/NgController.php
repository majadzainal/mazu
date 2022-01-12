<?php

namespace App\Http\Controllers\Master;

use Throwable;
use App\Models\Master\Ng;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class NgController extends Controller
{
    public $MenuID = '0213';

    public function listNg(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return redirect()->back();
        }

        return view('master.ngTable', [
            'MenuID'       => $this->MenuID,
        ]);

    }

    public function loadNg(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $ngList = Ng::where('is_active', 1)->get();
        return['data'=> $ngList];
    }

    public function addNg(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            Ng::create([
                'ng_name'           => $request->ng_name,
                'description'       => $request->description,
                'is_active'         => 1,
            ]);

            return response()->json(['status' => 'Success', 'message' => 'Add NG success.'], 200);

        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function updateNg(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $ng = Ng::find($request->ng_id);
            if($ng){
                $ng->update([
                    'ng_name'       => $request->ng_name,
                    'description'   => $request->description,
                ]);

                return response()->json(['status' => 'Success', 'message' => 'Edit NG success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'NG not found.'], 200);
            }
        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function deleteNg($id)
    {
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $ng = Ng::find($id);
            if ($ng){
                $ng->is_active = 0;
                $ng->update();

                return response()->json(['status' => 'Success', 'message' => 'Delete NG success.'], 200);
            } else {
                return response()->json(['status' => 'info', 'message' => 'Delete NG failed.'], 202);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }
    }
}
