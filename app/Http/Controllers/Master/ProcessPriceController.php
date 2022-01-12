<?php

namespace App\Http\Controllers\Master;

use Throwable;
use Illuminate\Http\Request;
use App\Models\Master\Process;
use Illuminate\Support\Facades\DB;
use App\Models\Master\ProcessPrice;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Master\ProcessMachine;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProcessPriceController extends Controller
{
    public $MenuID = '0211';
    public function listProcessPrice(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        $processList = Process::where('is_active', 1)->select('process_id', 'process_name')->get();
        $machineList = ProcessMachine::where('is_active', 1)->select('pmachine_id', 'code', 'brand', 'line')->get();

        return view('master.processPriceTable', [
            'MenuID'        => $this->MenuID,
            'processList'      => $processList,
            'machineList'      => $machineList,
        ]);

    }

    function loadProcessPrice(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $processPriceList = ProcessPrice::where('is_active', 1)
                ->select('process_id', 'pmachine_id')
                ->groupby('process_id', 'pmachine_id')->distinct()->with('process', 'processMachine')->get();


        return['data'=> $processPriceList];
    }

    function getProcessPrice($process_id, $pmachine_id){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $processPriceList = ProcessPrice::where('is_active', 1)
                ->where('process_id', $process_id)
                ->where('pmachine_id', $pmachine_id)
                ->with('process', 'processMachine')->get();

        return['data'=> $processPriceList];
    }

    function getProcessPriceActive($process_id, $pmachine_id, $date){
        $processPriceList = ProcessPrice::where('is_active', 1)
                ->where('process_id', $process_id)
                ->where('pmachine_id', $pmachine_id)
                ->where('effective_date', '>=', $date)->get();

        return['data'=> $processPriceList];
    }

    function addProcessPrice(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        $validator = Validator::make($request->all(), [
            'process_id_price' => 'required',
            'pmachine_id_price' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'Error', 'message' => 'Please select process name and machine.'], 202);
        }

        DB::beginTransaction();
        try {
            $arrsuccess = 0;
            for ($i=0; $i<count($request->price); $i++ ){

                $price = ProcessPrice::create([
                    'process_id'       => $request->process_id_price,
                    'pmachine_id'       => $request->pmachine_id_price,
                    'cycle'             => $request->cycle,
                    'effective_date'    => $request->effective_date[$i],
                    'price'             => $request->price[$i],
                    'is_active'         => 1,
                    'created_user'        => Auth::User()->employee->employee_name,
                ]);

                if($price)
                    $arrsuccess++;
            }

            if (count($request->price) == $arrsuccess){
                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'add process price success.'], 200);
            } else {
                DB::rollback();
                return response()->json(['status' => 'Info', 'message' => 'check the price list again.'], 200);
            }

        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }

    function updateProcessPrice(Request $request){
        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        $validator = Validator::make($request->all(), [
            'process_id_price' => 'required',
            'pmachine_id_price' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'Error', 'message' => 'Please select process name and machine.'], 202);
        }

        DB::beginTransaction();
        try {

            $deletedRows = ProcessPrice::where('process_id', $request->process_id_price)
                        ->where('pmachine_id', $request->pmachine_id_price)->get();

            foreach ($deletedRows as $ls) {
                $ls->is_active          = 0;
                $ls->updated_user       = Auth::User()->employee->employee_name;
                $ls->update();
            }

            $arrsuccess = 0;
            for ($i=0; $i<count($request->price); $i++ ){

                if($request->process_price_id[$i]){
                    $data = ProcessPrice::where('process_price_id', $request->process_price_id[$i])->get()->first();
                    $data->process_id      = $request->process_id_price;
                    $data->pmachine_id      = $request->pmachine_id_price;
                    $data->effective_date   = $request->effective_date[$i];
                    $data->price            = $request->price[$i];
                    $data->is_active        = 1;
                    $data->updated_user     = Auth::User()->employee->employee_name;
                    $data->update();

                    if($data)
                    $arrsuccess++;
                }else{
                    $price = ProcessPrice::create([
                        'process_id'       => $request->process_id_price,
                        'pmachine_id'       => $request->pmachine_id_price,
                        'cycle'             => $request->cycle,
                        'effective_date'         => $request->effective_date[$i],
                        'price'             => $request->price[$i],
                        'is_active'         => 1,
                        'created_user'        => Auth::User()->employee->employee_name,
                    ]);

                    if($price)
                    $arrsuccess++;
                }
            }

            if (count($request->price) == $arrsuccess){
                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'Update process price success.'], 200);
            } else {
                DB::rollback();
                return response()->json(['status' => 'Info', 'message' => 'check the price list again.'], 200);
            }

        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }

    public function deleteProcessPrice($process_id, $pmachine_id)
    {
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        DB::beginTransaction();
        try {

            $deletedRows = ProcessPrice::where('process_id', $process_id)
                        ->where('pmachine_id', $pmachine_id)->get();

            foreach ($deletedRows as $ls) {
                $ls->is_active          = 0;
                $ls->updated_user       = Auth::User()->employee->employee_name;
                $ls->update();
            }
            DB::commit();
            return response()->json(['status' => 'Success', 'message' => 'Delete Process price success.'], 200);

        } catch (Throwable $e){
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }
}
