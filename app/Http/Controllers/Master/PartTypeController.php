<?php

namespace App\Http\Controllers\Master;

use Throwable;
use Illuminate\Http\Request;
use App\Models\Master\PartType;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PartTypeController extends Controller
{
    public $MenuID = '0208';

    public function loadPartType(){

        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $partTypeList = PartType::where('is_active', 1)->get();

        return['data'=> $partTypeList];
    }

    public function addPartType(Request $request){
        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        $isExist = PartType::where('code', $request->code)->pluck('part_type')->first();
        if($isExist != null){
            return response()->json(['status' => 'Info', 'message' => 'part type already exists'], 200);
        }


        try {
            $partType = PartType::create([
                'code'          => $request->code,
                'part_type'     => $request->part_type,
                'is_active'        => 1,
            ]);
            if($partType){
                return response()->json(['status' => 'Success', 'message' => 'Add part type success.'], 200);
            }else{
                return response()->json(['status' => 'Error', 'message' => 'Add part type failled.'], 200);
            }

        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function updatePartType(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {
            $partType = PartType::find($request->part_type_id);
            if($partType){
                $partType->update([
                    'part_type'    => $request->part_type,
                ]);

                return response()->json(['status' => 'Success', 'message' => 'Edit part type success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'Part type not found.'], 200);
            }
        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function deletePartType($id)
    {

        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $partType = PartType::find($id);
            if ($partType){
                $partType->is_active = 0;
                $partType->update();

                return response()->json(['status' => 'Success', 'message' => 'Delete part type success.'], 200);
            } else {
                return response()->json(['status' => 'info', 'message' => 'Delete part type failed.'], 202);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => 'Delete part type failed.'], 200);
        }
    }
}
