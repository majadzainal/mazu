<?php

namespace App\Http\Controllers\Master;

use Throwable;
use Illuminate\Http\Request;
use App\Models\Master\Divisi;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\PartType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DivisiController extends Controller
{
    public $MenuID = '0208';

    public function listDivisiImport(){

        $divisiList = Divisi::where('is_active', 1)
                    ->where('is_production', 1)
                    ->with('partType')
                    ->get();

        return view('master.import.divisiList', [
            'divisiList' => $divisiList,
        ]);
    }

    public function listDivisi(){
        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return redirect()->back();
        }

        $partTypeList = PartType::where('is_active', 1)->orderBy('created_at', 'DESC')->get();

        return view('master.divisiTable', [
            'MenuID' => $this->MenuID,
            'partTypeList' => $partTypeList,
        ]);
    }

    public function loadDivisi($part_type){

        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        if($part_type === "all"){
            $positionList = Divisi::where('is_active', 1)->orderBy('created_at', 'DESC')->get();
        }else{
            $positionList = Divisi::where('is_active', 1)
                        ->where('part_type_id', $part_type)
                        ->orderBy('created_at', 'DESC')->get();
        }
        return['data'=> $positionList];
    }

    public function addDivisi(Request $request){
        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {
            if($request->part_type_id_divisi){
                $divisiExist = Divisi::where('divisi_code', $request->divisi_code)
                    ->where('part_type_id', $request->part_type_id_divisi)
                    ->pluck('divisi_name')->first();

                if($divisiExist){
                    return response()->json(['status' => 'Error', 'message' => 'Divisi Code is Exist in part type.'.$divisiExist.''], 200);
                }
            }

            $divisi = Divisi::create([
                'divisi_code'    => $request->divisi_code,
                'part_type_id'   => $request->part_type_id_divisi ? $request->part_type_id_divisi : null,
                'divisi_name'    => $request->divisi_name,
                'is_production'  => $request->is_production,
                'is_active'        => 1,
            ]);
            if($divisi){
                return response()->json(['status' => 'Success', 'message' => 'Add divisi success.'], 200);
            }else{
                return response()->json(['status' => 'Error', 'message' => 'Add divisi failled.'], 200);
            }

        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function updateDivisi(Request $request){
        // dd($request);
        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {
            if($request->part_type_id_divisi){
                $divisiExist = Divisi::where('divisi_code', $request->divisi_code)
                    ->where('part_type_id', $request->part_type_id_divisi)
                    ->whereNotIn('divisi_id', [$request->divisi_id,])
                    ->pluck('divisi_name')->first();

                if($divisiExist){
                    return response()->json(['status' => 'Error', 'message' => 'Divisi Code is Exist in part type.'.$divisiExist.''], 200);
                }
            }

            $divisi = Divisi::find($request->divisi_id);
            if($divisi){
                $divisi->update([
                    'divisi_code'    => $request->divisi_code,
                    'divisi_name'    => $request->divisi_name,
                    'is_production'    => $request->is_production,
                    'part_type_id'    => $request->part_type_id_divisi ? $request->part_type_id_divisi : null,
                ]);

                return response()->json(['status' => 'Success', 'message' => 'Edit divisi success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'Divisi not found.'], 200);
            }
        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function deleteDivisi($id)
    {

        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $divisi = Divisi::find($id);
            if ($divisi){
                $divisi->is_active = 0;
                $divisi->update();

                return response()->json(['status' => 'Success', 'message' => 'Delete divisi success.'], 200);
            } else {
                return response()->json(['status' => 'info', 'message' => 'Delete divisi failed.'], 202);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => 'Delete divisi failed.'], 200);
        }
    }
}
