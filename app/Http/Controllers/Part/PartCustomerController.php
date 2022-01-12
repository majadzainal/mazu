<?php

namespace App\Http\Controllers\Part;

use Throwable;
use Ramsey\Uuid\Uuid;
use App\Models\Part\Stock;
use App\Models\Master\Unit;
use App\Models\Master\Plant;
use Illuminate\Http\Request;
use App\Models\Master\Divisi;
use App\Models\Master\Status;
use App\Models\Part\PartPrice;
use App\Models\Master\Customer;
use App\Models\Master\Warehouse;
use App\Models\Part\PartCustomer;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\Part\StockController;
use App\Http\Controllers\Part\BillProcessController;
use App\Http\Controllers\Part\BillMaterialController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Process\OpnameScheduleController;

class PartCustomerController extends Controller
{
    public $MenuID = '0302';

    public $objStock;
    public $objBOM;
    public $objBOP;
    public $objOpnameSchedule;

    public function __construct()
    {
        $this->objStock = new StockController();
        $this->objBOM = new BillMaterialController();
        $this->objBOP = new BillProcessController();
        $this->objOpnameSchedule = new OpnameScheduleController();
    }

    public function listPartCustomerImport(){
        $partCustomerList = PartCustomer::where('is_active', 1)->with('unit')->get();

        return view('master.import.partCustomerList', [
            'partCustomerList' => $partCustomerList,
        ]);
    }

    public function listPartCustomer(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        $customerList = Customer::where('is_active', 1)->get();
        $divisiList = Divisi::where('is_production', 1)->where('is_active', 1)->select('divisi_id', 'divisi_name')->get();
        $unitList = Unit::where('is_active', 1)->select('unit_id', 'unit_name')->get();
        $plantList = Plant::where('is_active', 1)->select('plant_id', 'plant_name')->get();
        $statusList = Status::where('is_active', 1)->where('status_type', 'engineering')->select('status_id', 'status')->get();
        $warehouseList = Warehouse::where('is_active', 1)->select('warehouse_id', 'warehouse_name')->get();

        return view('part.partCustomerTable', [
            'MenuID'        => $this->MenuID,
            'customerList'  => $customerList,
            'unitList'      => $unitList,
            'divisiList'    => $divisiList,
            'plantList'     => $plantList,
            'statusList'    => $statusList,
            'warehouseList' => $warehouseList
        ]);

    }

    public function loadPartCustomer(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $partList = PartCustomer::where('is_active', 1)->with('customer', 'part_price', 'divisi', 'unit', 'stock_warehouse')->orderBy('created_at', 'DESC')->get();
        return['data'=> $partList];
    }

    public function loadPartCustomerByCustomerId($customer_id){
        $dataList = PartCustomer::where('customer_id', $customer_id)->where('is_active', 1)
                    ->where('is_supplier', 0)
                    ->with('divisi', 'plant', 'unit', 'part_price', 'bom.bom_item.part_customer.part_price', 'bom.bom_item.part_supplier.part_price', 'bop.bop_item')->get();

        $partList = $dataList->map(function($item){
            $data['part_customer_id'] = $item->part_customer_id;
            $data['part_number'] = $item->part_number;
            $data['part_name'] = $item->part_name;
            $data['part_price'] = $this->getPrice($item->part_customer_id);
            $data['costBOM'] = $this->objBOM->getCostBillMaterial($item->part_customer_id);
            $data['costBOP'] = $this->objBOP->getCostBillProcess($item->part_customer_id);
            $data['divisi_id'] = $item->divisi->divisi_id;
            $data['divisi_name'] = $item->divisi->divisi_name;
            $data['unit_id'] = $item->unit->unit_id;
            $data['unit_name'] = $item->unit->unit_name;
            $data['plant_id'] = $item->plant->plant_id;
            $data['plant_name'] = $item->plant->plant_name;
            return $data;
        });

        return['data'=> $partList];
    }

    function getPrice($part_customer_id){
        $part_price = PartPrice::where('part_customer_id', $part_customer_id)
                ->where('effective_date', '<=', date("Y-m-d"))
                ->where('is_active', 1)
                ->orderBy('effective_date', 'DESC')
                ->first();

        return $part_price ? $part_price->price : 0;
    }

    public function addPartCustomer(Request $request){


        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        DB::beginTransaction();
        try {
            $countPrice = $request->price ? count($request->price) : 0;
            $countStock = $request->stock ? count($request->stock) : 0;

            $partName = trim(preg_replace('/[\t\n\r\s]+/', ' ', $request->part_name)," ");
            $partExist = PartCustomer::where('customer_id', $request->customer_id)
                                ->whereRaw('TRIM(part_name) = ? ', [$partName])
                                ->pluck('part_name')->first();
                                //->where('part_name', $request->part_name)->pluck('part_name')->first();

            if($partExist != null){
                return response()->json(['status' => 'Info', 'message' => 'part name already exists'], 200);
            }

            $partId = Uuid::uuid4()->toString();
            $part = PartCustomer::create([
                'part_customer_id'  => $partId,
                'customer_id'       => $request->customer_id,
                'divisi_id'         => $request->divisi_id,
                'part_number'       => $request->part_number,
                'part_name'         => $partName,
                'add_date'          => $request->add_date,
                'plant_id'          => $request->plant_id,
                'unit_id'           => $request->unit_id,
                'is_supplier'       => $request->is_supplier,
                'status'            => $request->status,
                'created_user'      => Auth::User()->employee->employee_name,
                'is_active'         => 1,
            ]);

            if ($part){

                $arrsuccess = 0;
                if($request->price){

                    for ($i=0; $i<count($request->price); $i++ ){

                        $price = PartPrice::create([
                            'part_customer_id'  => $partId,
                            'effective_date'    => $request->effective_date[$i],
                            'price'             => $request->price[$i],
                            'is_active'         => 1,
                            'created_user'      => Auth::User()->employee->employee_name,
                        ]);

                        if($price)
                            $arrsuccess++;
                    }
                }

                if($request->stock){
                    for ($i=0; $i<count($request->stock); $i++ ){

                        $this->objStock->plusStockPartCustomer($partId, $request->warehouse_id[$i], $request->stock[$i], "Add Part Customer");
                        $arrsuccess++;
                    }
                }
                if (($countPrice + $countStock) == $arrsuccess){
                    DB::commit();
                    return response()->json(['status' => 'Success', 'message' => 'add part success.'], 200);
                } else {
                    DB::rollback();
                    return response()->json(['status' => 'Info', 'message' => 'check the price list again.'], 200);
                }

            } else {
                return response()->json(['status' => 'Info', 'message' => 'add part Failed.'], 200);

            }

        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }

    }

    public function updatePartCustomer(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        DB::beginTransaction();
        try {
            $countPrice = $request->price ? count($request->price) : 0;
            $countStock = $request->stock ? count($request->stock) : 0;

            $partName = trim(preg_replace('/[\t\n\r\s]+/', ' ', $request->part_name)," ");
            $part = PartCustomer::find($request->part_customer_id);
            $part->update([
                'customer_id'       => $request->customer_id,
                'divisi_id'         => $request->divisi_id,
                'part_number'       => $request->part_number,
                'part_name'         => $partName,
                'add_date'          => $request->add_date,
                'unit_id'           => $request->unit_id,
                'plant_id'          => $request->plant_id,
                'is_supplier'       => $request->is_supplier,
                'status'            => $request->status,
                'updated_user'      => Auth::User()->employee->employee_name,
            ]);

            if ($part){
                PartPrice::where('part_customer_id', $request->part_customer_id)->update([
                    'is_active'         => 0,
                    'updated_user'      => Auth::User()->employee->employee_name,
                ]);

                $arrsuccess = 0;
                if($request->price){
                    for ($i=0; $i<count($request->price); $i++ ){
                        $priceUpdate = PartPrice::find($request->price_id[$i]);
                        if ($priceUpdate){
                            $priceUpdate->update([
                                'part_customer_id'  => $request->part_customer_id,
                                'effective_date'    => $request->effective_date[$i],
                                'price'             => $request->price[$i],
                                'is_active'         => 1,
                                'updated_user'      => Auth::User()->employee->employee_name,
                            ]);
                        } else {
                            $priceCreate = PartPrice::create([
                                'part_customer_id'  => $request->part_customer_id,
                                'effective_date'    => $request->effective_date[$i],
                                'price'             => $request->price[$i],
                                'is_active'         => 1,
                                'created_user'      => Auth::User()->employee->employee_name,
                            ]);
                        }

                        if($priceUpdate || $priceCreate)
                            $arrsuccess++;
                    }
                }

                if($request->stock){
                    for ($i=0; $i<count($request->stock); $i++ ){
                        $this->objStock->plusStockPartCustomer($part->part_customer_id, $request->warehouse_id[$i], $request->stock[$i], "Edit Part Customer");
                    }
                }
                if (($countPrice + $countStock) == $arrsuccess){
                    DB::commit();
                    return response()->json(['status' => 'Success', 'message' => 'update part success.'], 200);
                } else {
                    DB::rollback();
                    return response()->json(['status' => 'Info', 'message' => 'check the price list again.'], 200);
                }
            } else {
                return response()->json(['status' => 'Info', 'message' => 'update part failed.'], 200);
            }

        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }

    }

    public function deletePartCustomer($id)
    {

        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $part = PartCustomer::find($id);
            if ($part){
                $part->is_active = 0;
                $part->update();

                return response()->json(['status' => 'Success', 'message' => 'delete part success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'delete part failed.'], 200);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }

    public function importPartCustomer(Request $request)
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
            $message = "import part success.";
            $existList = "";
            foreach($dataList as $ls){
                $partExist = null;
                $partExist = PartCustomer::where('customer_id', $ls['customer_id'])
                                    ->where('part_name', $ls['part_name'])->pluck('part_name')->first();
                if($partExist != null){
                    $existList .= $partExist. ", ";
                    continue;
                }
                $partId = Uuid::uuid4()->toString();
                $part = PartCustomer::create([
                    'part_customer_id'  => $partId,
                    'customer_id'       => $ls['customer_id'],
                    'divisi_id'         => $ls['divisi_id'],
                    'part_number'       => $ls['part_number'],
                    'part_name'         => $ls['part_name'],
                    'add_date'          => $ls['add_date'],
                    'unit_id'           => $ls['unit_id'],
                    'plant_id'          => $ls['plant_id'],
                    'status'            => $ls['status_id'],
                    'is_supplier'       => strtolower($ls['wip']) == 'yes' ? 1 : 0,
                    'is_active'         => 1,
                ]);

                if ($part){
                    $price = PartPrice::create([
                        'part_customer_id'  => $partId,
                        'effective_date'    => $ls['effective_date'],
                        'price'             => $ls['price'],
                        'is_active'         => 1,
                        'created_user'      => Auth::User()->employee->employee_name,
                    ]);
                    $this->objStock->plusStockPartCustomer($partId, $ls['warehouse'], $ls['stock'], "Add Part Customer");
                }
            }

            if($existList !== ""){
                $message .= " With error (existing part name ".$existList." )";
            }

            DB::commit();
            return response()->json(['status' => 'Success', 'message' => $message], 200);

        }catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }
}
