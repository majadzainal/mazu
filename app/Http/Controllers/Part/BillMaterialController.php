<?php

namespace App\Http\Controllers\Part;

use Throwable;
use App\Models\Part\BillMaterial;
use App\Models\Part\BillMaterialItem;
use App\Models\Part\PartCustomer;
use App\Models\Part\PartSupplier;
use App\Models\Master\Customer;
use App\Models\Master\Unit;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BillMaterialController extends Controller
{
    public $MenuID = '0303';

    public function listBillMaterial(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }


        $customerList = Customer::where('is_active', 1)->get();
        $partCustomerList = PartCustomer::where('is_active', 1)->with('customer', 'part_price', 'divisi', 'unit')->get();
        $partCustomerIsSupplierList = PartCustomer::where('is_active', 1)->where('is_supplier', 1)->with('customer', 'part_price', 'divisi', 'unit')->get();
        $partSupplierList = PartSupplier::where('is_active', 1)->with('supplier', 'part_price', 'divisi', 'unit')->get();
        $unitList = Unit::where('is_active', 1)->select('unit_id', 'unit_name')->get();
        $partList = $partSupplierList->merge($partCustomerIsSupplierList);
        return view('part.billMaterialTable', [
            'MenuID'            => $this->MenuID,
            'customerList'      => $customerList,
            'partCustomerList'  => $partCustomerList,
            'partSupplierList'  => $partSupplierList,
            'unitList'          => $unitList,
            'partList'          => $partList
        ]);

    }

    public function loadBillMaterial(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $billMaterialList = BillMaterial::where('is_active', 1)
                                        ->with('customer', 'part_customer', 'bom_item')
                                        ->get();

        return['data'=> $billMaterialList];
    }

    public function addBillMaterial(Request $request){


        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        DB::beginTransaction();
        try {
            $arrsuccess = 0;

            $bomExist = BillMaterial::where('customer_id', $request->customer_id)
                                    ->where('part_customer_id', $request->part_customer_id)
                                    ->where('is_active', 1)->count();
            if($bomExist){
                DB::rollback();
                return response()->json(['status' => 'Info', 'message' => 'Customer and Part already exist.'], 200);
            }

            $bomId = Uuid::uuid4()->toString();
            $bom = BillMaterial::create([
                'bill_material_id'  => $bomId,
                'customer_id'       => $request->customer_id,
                'part_customer_id'  => $request->part_customer_id,
                'date_input'        => $request->date_input,
                'status_id'         => $request->status_id,
                'is_active'         => 1,
                'created_user'      => Auth::User()->employee->employee_name,
            ]);

            if ($bom){
                for ($i=0; $i<count($request->part_id); $i++ ){

                    $item = BillMaterialItem::create([
                        'bill_material_id'  => $bomId,
                        'part_id'           => $request->part_id[$i],
                        'amount_usage'      => $request->amount_usage[$i],
                        'unit_id'           => $request->unit_id[$i],
                        'price'             => $request->price[$i],
                        'cost'              => $request->cost[$i],
                        'is_active'         => 1,
                        'created_user'      => Auth::User()->employee->employee_name,
                    ]);

                    if($item)
                        $arrsuccess++;

                }

                if (count($request->part_id) == $arrsuccess ){
                    DB::commit();
                    return response()->json(['status' => 'Success', 'message' => 'add Bill of Material success.'], 200);
                } else {
                    DB::commit();
                    return response()->json(['status' => 'Success', 'message' => 'add Bill of Material success, with a material error.' ], 200);
                }
            }

        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }

    }

    public function updateBillMaterial(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        DB::beginTransaction();

        try {

            $arrsuccess = 0;

            $bom = BillMaterial::find($request->bill_material_id);
            $bom->update([
                'customer_id'       => $request->customer_id,
                'part_customer_id'  => $request->part_customer_id,
                'date_input'        => $request->date_input,
                'status_id'         => $request->status_id,
                'is_active'         => 1,
                'updated_user'      => Auth::User()->employee->employee_name,
            ]);

            if($bom){

                BillMaterialItem::where('bill_material_id', $request->bill_material_id)->update([
                    'is_active'         => 0,
                    'updated_user'      => Auth::User()->employee->employee_name,
                ]);

                for ($i=0; $i<count($request->part_id); $i++ ){
                    $itemUpdate = BillMaterialItem::find($request->item_bom_id[$i]);
                    if ($itemUpdate){
                        $itemUpdate->update([
                            'bill_material_id'  => $request->bill_material_id,
                            'part_id'           => $request->part_id[$i],
                            'amount_usage'      => $request->amount_usage[$i],
                            'unit_id'           => $request->unit_id[$i],
                            'price'             => $request->price[$i],
                            'cost'              => $request->cost[$i],
                            'is_active'         => 1,
                            'updated_user'      => Auth::User()->employee->employee_name,
                        ]);
                    } else {
                        $itemCreate = BillMaterialItem::create([
                            'bill_material_id'  => $request->bill_material_id,
                            'part_id'           => $request->part_id[$i],
                            'amount_usage'      => $request->amount_usage[$i],
                            'unit_id'           => $request->unit_id[$i],
                            'price'             => $request->price[$i],
                            'cost'              => $request->cost[$i],
                            'is_active'         => 1,
                            'created_user'      => Auth::User()->employee->employee_name,
                        ]);
                    }

                    if($itemUpdate || $itemCreate)
                        $arrsuccess++;
                }
            }

            if (count($request->part_id) == $arrsuccess){
                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'edit Bill of Material success.'], 200);
            } else {
                DB::rollback();
                return response()->json(['status' => 'Info', 'message' => 'check the price list again.'], 200);
            }
        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }

    }

    public function deleteBillMaterial($id)
    {

        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $bom = BillMaterial::find($id);
            if ($bom){
                $bom->is_active = 0;
                $bom->update();

                return response()->json(['status' => 'Success', 'message' => 'delete bill of material success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'delete bill of material failed.'], 200);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }

    public function importBillMaterial(Request $request)
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
            $message = "import bill of material success.";
            $existList = "";
            foreach($dataList as $ls){
                $bomExist = null;
                $bomExist = BillMaterial::where('customer_id', $ls['customer_id'])
                                        ->where('part_customer_id', $ls['part_customer_id'])
                                        ->where('is_active', 1)
                                        ->first();

                if($bomExist != null){
                    //$existList .= $ls['part_customer_id']. ", ";
                    //continue;

                    $item = BillMaterialItem::create([
                        'bill_material_id'  => $bomExist->bill_material_id,
                        'part_id'           => $ls['part_supplier_id'],
                        'amount_usage'      => $ls['amount_usage'],
                        'unit_id'           => $ls['unit_id'],
                        'price'             => $ls['price'],
                        'cost'              => $ls['cost'],
                        'is_active'         => 1,
                        'created_user'      => Auth::User()->employee->employee_name,
                    ]);

                } else {

                    $bomId = Uuid::uuid4()->toString();
                    $bom = BillMaterial::create([
                        'bill_material_id'  => $bomId,
                        'customer_id'       => $ls['customer_id'],
                        'part_customer_id'  => $ls['part_customer_id'],
                        'date_input'        => $ls['date_input'],
                        'status_id'         => $ls['status_id'],
                        'is_active'         => 1,
                        'created_user'      => Auth::User()->employee->employee_name,
                    ]);

                    if ($bom){
                        $item = BillMaterialItem::create([
                            'bill_material_id'  => $bomId,
                            'part_id'           => $ls['part_supplier_id'],
                            'amount_usage'      => $ls['amount_usage'],
                            'unit_id'           => $ls['unit_id'],
                            'price'             => $ls['price'],
                            'cost'              => $ls['cost'],
                            'is_active'         => 1,
                            'created_user'      => Auth::User()->employee->employee_name,
                        ]);
                    }
                }

            }

            if($existList !== ""){
                $message .= " With error (existing Part Customer Name ".$existList." )";
            }

            DB::commit();
            return response()->json(['status' => 'Success', 'message' => $message], 200);

        }catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }

    public function getDataBillMaterial($id){

        $billMaterialList = BillMaterial::where('is_active', 1)
                                        ->where('part_customer_id', $id)
                                        ->with('bom_item')
                                        ->first();

        return['data'=> $billMaterialList];
    }

    public function getCostBillMaterial($id){

        $billMaterialList = BillMaterial::where('is_active', 1)
                                        ->where('part_customer_id', $id)
                                        ->with('bom_item.part_supplier.part_price', 'bom_item.part_customer')
                                        ->first();
        $bomPrice = 0;
        $partPrice = 0;
        $amountUsage = 0;

        if($billMaterialList != ""){
            foreach($billMaterialList->bom_item as $ls){
                $partPrice = 0;
                $amountUsage = $ls->amount_usage;

                if($ls->part_supplier){
                    $price = $ls->part_supplier->part_price->where('effective_date', '<=', date("Y-m-d"))
                                                ->where('is_active', 1)
                                                ->sortByDesc('effective_date')
                                                ->first();

                    if ($price !== ""){
                        $partPrice = $price->price;
                    }
                }

                if($ls->part_customer){
                    $price = $this->getCostBillMaterial($ls->part_customer->part_customer_id);
                    if ($price !== ""){
                        $partPrice = $price['data'];
                    }
                }


                $bomPrice += $partPrice * $amountUsage;
            }
        }


        return['data'=> $bomPrice];
    }
}
