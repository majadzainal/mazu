<?php

namespace App\Http\Controllers\Master;

use Throwable;
use App\Models\Master\Unit;
use App\Models\Master\Plant;
use Illuminate\Http\Request;
use App\Models\Master\Divisi;
use App\Models\Master\Process;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Master\ProcessMachine;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProcessMachineController extends Controller
{
    public $MenuID = '0206';

    public function listPMachineImport(){
        $pmachineList = ProcessMachine::where('is_active', 1)->get();

        return view('master.import.pmachineList', [
            'pmachineList' => $pmachineList,
        ]);
    }

    public function listProcessMachine(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        $divisiList = Divisi::where('is_production', 1)->where('is_active', 1)->select('divisi_id', 'divisi_name')->get();
        $plantList = Plant::where('is_active', 1)->select('plant_id', 'plant_name')->get();
        $unitList = Unit::where('is_active', 1)->select('unit_id', 'unit_name')->get();
        $processList = Process::where('is_active', 1)->select('process_id', 'process_name')->get();
        $machineList = ProcessMachine::where('is_active', 1)->select('pmachine_id', 'code', 'brand', 'line')->get();

        return view('master.machineTable', [
            'MenuID'        => $this->MenuID,
            'divisiList'    => $divisiList,
            'planList'     => $plantList,
            'unitList'      => $unitList,
            'processList'      => $processList,
            'machineList'      => $machineList,
        ]);

    }

    public function loadProcessMachine(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $machineList = ProcessMachine::where('is_active', 1)->with('divisi', 'plant', 'unit')->orderBy('created_at', 'DESC')->get();
        return['data'=> $machineList];
    }

    public function addProcessMachine(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $machine = ProcessMachine::create([
                'divisi_id'     => $request->divisi_id,
                'plant_id'      => $request->plant_id,
                'code'          => $request->code,
                'brand'         => $request->brand,
                'line'          => $request->line,
                'spec_volume'   => $request->spec_volume,
                'spec_unit'     => $request->spec_unit,
                'cycle_time'    => $request->cycle_time,
                'uos'           => $request->uos,
                'created_user'  => Auth::User()->employee->employee_name,
                'is_active'     => 1,
            ]);

            if ($machine){
                return response()->json(['status' => 'Success', 'message' => 'add process machine success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'add process machine Failed.'], 200);
            }

        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }

    }

    public function updateProcessMachine(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {
            $machine = ProcessMachine::find($request->pmachine_id);
            $machine->update([
                'divisi_id'     => $request->divisi_id,
                'plant_id'      => $request->plant_id,
                'code'          => $request->code,
                'brand'         => $request->brand,
                'line'          => $request->line,
                'spec_volume'   => $request->spec_volume,
                'spec_unit'     => $request->spec_unit,
                'cycle_time'    => $request->cycle_time,
                'uos'           => $request->uos,
                'updated_user'  => Auth::User()->employee->employee_name,
            ]);

            if ($machine){
                return response()->json(['status' => 'Success', 'message' => 'update process machine success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'update process machine failed.'], 200);
            }

        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }

    }

    public function deleteProcessMachine($id)
    {

        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $machine = ProcessMachine::find($id);
            if ($machine){
                $machine->is_active = 0;
                $machine->updated_user = Auth::User()->employee->employee_name;
                $machine->update();

                return response()->json(['status' => 'Success', 'message' => 'delete process machine success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'delete process machine failed.'], 200);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }
}
