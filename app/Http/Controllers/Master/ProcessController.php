<?php

namespace App\Http\Controllers\Master;

use Throwable;
use Illuminate\Http\Request;
use App\Models\Master\Process;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProcessController extends Controller
{
    public $MenuID = '0210';

    public function listProcessImport(){
        $processList = Process::where('is_active', 1)->get();

        return view('master.import.processList', [
            'processList' => $processList,
        ]);
    }
    public function listProcess(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        return view('master.processTable', [
            'MenuID'        => $this->MenuID,
        ]);

    }

    public function loadprocess(){

        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $processList = Process::where('is_active', 1)->orderBy('created_at', 'DESC')->get();

        return['data'=> $processList];
    }

    public function addProcess(Request $request){

        try {
            if(!isAccess('create', $this->MenuID)){
                return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
            }
            if(isOpname()){
                return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
            }

            $processName = trim(preg_replace('/[\t\n\r\s]+/', ' ', $request->process_name)," ");
            $processExist = Process::whereRaw('TRIM(process_name) = ? ', [$processName])
                                ->pluck('process_name')->first();
                                //->where('part_name', $request->part_name)->pluck('part_name')->first();

            if($processExist != null){
                return response()->json(['status' => 'Info', 'message' => 'process name already exists'], 200);
            }


            $process = Process::create([
                'process_name'    => $request->process_name,
                'is_active'        => 1,
            ]);

            if($process){
                return response()->json(['status' => 'Success', 'message' => 'Add process success.'], 200);
            } else {
                return response()->json(['status' => 'Error', 'message' => 'Add process failled.'], 202);
            }
        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }
    public function updateProcess(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {
            $process = Process::find($request->process_id);
            if($process){
                $process->update([
                    'process_name'    => $request->process_name,
                ]);

                return response()->json(['status' => 'Success', 'message' => 'Edit process success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'process not found.'], 200);
            }
        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }
    }

    public function deleteprocess($id){

        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $process = Process::find($id);
            if ($process){
                $process->is_active = 0;
                $process->update();

                return response()->json(['status' => 'Success', 'message' => 'Delete process success.'], 200);
            } else {
                return response()->json(['status' => 'info', 'message' => 'Delete process failed.'], 202);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => 'Delete process failed.'], 200);
        }
    }
}
