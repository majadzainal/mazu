<?php

namespace App\Http\Controllers\Part;

use Throwable;
use Ramsey\Uuid\Uuid;
use App\Models\Master\Plant;
use Illuminate\Http\Request;
use App\Models\Master\Process;
use App\Models\Master\Customer;
use App\Models\Part\BillProcess;
use App\Models\Part\PartCustomer;
use Illuminate\Support\Facades\DB;
use App\Models\Master\ProcessPrice;
use App\Http\Controllers\Controller;
use App\Models\Part\BillProcessItem;
use Illuminate\Support\Facades\Auth;
use App\Models\Master\ProcessMachine;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BillProcessController extends Controller
{
    public $MenuID = '0304';

    public function listBillProcess(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        $customerList = Customer::where('is_active', 1)->get();
        $partCustomerList = PartCustomer::where('is_active', 1)->get();
        $plantList = Plant::where('is_active', 1)->select('plant_id', 'plant_name')->get();
        $processList = Process::where('is_active', 1)->select('process_id', 'process_name')->get();
        $machineList = ProcessMachine::where('is_active', 1)->select('pmachine_id', 'code')->get();
        $priceList = ProcessPrice::where('is_active', 1)->get();
        return view('part.billProcessTable', [
            'MenuID'            => $this->MenuID,
            'customerList'      => $customerList,
            'partCustomerList'  => $partCustomerList,
            'plantList'         => $plantList,
            'processList'       => $processList,
            'machineList'       => $machineList,
            'priceList'         => $priceList
        ]);

    }

    public function loadBillProcess(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $billProcessList = BillProcess::where('is_active', 1)
                                        ->with('customer', 'part_customer', 'plant', 'bop_item')
                                        ->orderBy('created_at', 'ASC')->orderBy('part_customer_id', 'ASC')
                                        ->get();

        return['data'=> $billProcessList];
    }

    public function addBillProcess(Request $request){


        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        DB::beginTransaction();
        try {
            $arrsuccess = 0;

            $bopExist = BillProcess::where('customer_id', $request->customer_id)
                                    ->where('part_customer_id', $request->part_customer_id)
                                    ->where('is_active', 1)->count();
            if($bopExist){
                DB::rollback();
                return response()->json(['status' => 'Info', 'message' => 'Customer and Part already exist.'], 200);
            }

            $bopId = Uuid::uuid4()->toString();
            $bop = BillProcess::create([
                'bill_process_id'   => $bopId,
                'customer_id'       => $request->customer_id,
                'part_customer_id'  => $request->part_customer_id,
                'status_id'         => $request->status_id,
                'plant_id'          => $request->plant_id,
                'is_active'         => 1,
                'created_user'      => Auth::User()->employee->employee_name,
            ]);

            if ($bop){
                for ($i=0; $i<count($request->process_id); $i++ ){

                    $item = BillProcessItem::create([
                        'bill_process_id'   => $bopId,
                        'process_order'     => $request->process_order[$i],
                        'process_id'        => $request->process_id[$i],
                        'cycle_time'        => $request->cycle_time[$i],
                        'mc'                => $request->mc[$i],
                        'is_active'         => 1,
                        'created_user'      => Auth::User()->employee->employee_name,
                    ]);

                    if($item)
                        $arrsuccess++;

                }

                if (count($request->process_id) == $arrsuccess ){
                    DB::commit();
                    return response()->json(['status' => 'Success', 'message' => 'add Bill of Process success.'], 200);
                } else {
                    DB::commit();
                    return response()->json(['status' => 'Success', 'message' => 'add Bill of Process success, with a material error.' ], 200);
                }
            }

        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }

    }

    public function updateBillProcess(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $arrsuccess = 0;

            $bop = BillProcess::find($request->bill_process_id);
            $bop->update([
                'customer_id'       => $request->customer_id,
                'part_customer_id'  => $request->part_customer_id,
                'status_id'         => $request->status_id,
                'plant_id'          => $request->plant_id,
                'is_active'         => 1,
                'updated_user'      => Auth::User()->employee->employee_name,
            ]);

            if($bop){

                BillProcessItem::where('bill_process_id', $request->bill_process_id)->update([
                    'is_active'         => 0,
                    'updated_user'      => Auth::User()->employee->employee_name,
                ]);

                for ($i=0; $i<count($request->process_id); $i++ ){
                    $itemUpdate = BillProcessItem::find($request->item_bop_id[$i]);
                    if ($itemUpdate){
                        $itemUpdate->update([
                            'bill_process_id'   => $request->bill_process_id,
                            'process_order'     => $request->process_order[$i],
                            'process_id'        => $request->process_id[$i],
                            'cycle_time'        => $request->cycle_time[$i],
                            'mc'                => $request->mc[$i],
                            'is_active'         => 1,
                            'updated_user'      => Auth::User()->employee->employee_name,
                        ]);
                    } else {
                        $itemCreate = BillProcessItem::create([
                            'bill_process_id'   => $request->bill_process_id,
                            'process_order'     => $request->process_order[$i],
                            'process_id'        => $request->process_id[$i],
                            'cycle_time'        => $request->cycle_time[$i],
                            'mc'                => $request->mc[$i],
                            'is_active'         => 1,
                            'created_user'      => Auth::User()->employee->employee_name,
                        ]);
                    }

                    if($itemUpdate || $itemCreate)
                        $arrsuccess++;
                }
            }

            if (count($request->process_id) == $arrsuccess){
                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'edit Bill of Process success.'], 200);
            } else {
                DB::rollback();
                return response()->json(['status' => 'Info', 'message' => 'check the process list again.'], 200);
            }
        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }

    }

    public function deleteBillProcess($id)
    {

        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $bom = BillProcess::find($id);
            if ($bom){
                $bom->is_active = 0;
                $bom->update();

                return response()->json(['status' => 'Success', 'message' => 'delete bill of process success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'delete bill of process failed.'], 200);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }

    public function importBillProcess(Request $request)
    {
        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        $dataList = json_decode($request->importList, TRUE);

        DB::beginTransaction();
        try {
            $message = "import bill of process success.";
            $existList = "";
            foreach($dataList as $ls){
                $bopExist = null;
                $bopExist = BillProcess::where('customer_id', $ls['customer_id'])
                                        ->where('part_customer_id', $ls['part_customer_id'])
                                        ->where('is_active', 1)
                                        ->first();

                if($bopExist != null){
                    //$existList .= $ls['process_name']. ", ";
                    //continue;
                    $item = BillProcessItem::create([
                        'bill_process_id'   => $bopExist->bill_process_id,
                        'process_order'     => $ls['process_order'],
                        'process_id'        => $ls['process_id'],
                        'cycle_time'        => $ls['cycle_time'],
                        'mc'                => $ls['mc'],
                        'is_active'         => 1,
                        'created_user'      => Auth::User()->employee->employee_name,
                    ]);
                } else {

                    $bopId = Uuid::uuid4()->toString();
                    $bop = BillProcess::create([
                        'bill_process_id'   => $bopId,
                        'customer_id'       => $ls['customer_id'],
                        'part_customer_id'  => $ls['part_customer_id'],
                        'plant_id'          => $ls['plant_id'],
                        'status_id'         => $ls['status_id'],
                        'is_active'         => 1,
                        'created_user'      => Auth::User()->employee->employee_name,
                    ]);

                    if ($bop){

                        $item = BillProcessItem::create([
                            'bill_process_id'   => $bopId,
                            'process_order'     => $ls['process_order'],
                            'process_id'        => $ls['process_id'],
                            'cycle_time'        => $ls['cycle_time'],
                            'mc'                => $ls['mc'],
                            'is_active'         => 1,
                            'created_user'      => Auth::User()->employee->employee_name,
                        ]);
                    }
                }

            }

            if($existList !== ""){
                $message .= " With error (existing process name ".$existList." )";
            }

            DB::commit();
            return response()->json(['status' => 'Success', 'message' => $message], 200);

        }catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }

    public function getCostBillProcess($id){

        $billProcessList = BillProcess::where('is_active', 1)
                                        ->where('part_customer_id', $id)
                                        ->with('bop_item.process_price')
                                        ->first();
        $costBop = 0;

        if($billProcessList != ""){
            foreach($billProcessList->bop_item as $ls){
                $cycle_time = $ls->cycle_time;
                if($ls->process_price != "")
                    $price = $ls->process_price->price;

                $costBop += $price * $cycle_time;

            }
        }
        return['data'=> $costBop];
    }
}
