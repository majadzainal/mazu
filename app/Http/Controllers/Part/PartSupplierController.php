<?php

namespace App\Http\Controllers\Part;

use Throwable;
use Ramsey\Uuid\Uuid;
use App\Models\Part\Stock;
use App\Models\Master\Unit;
use Illuminate\Http\Request;
use App\Models\Master\Divisi;
use App\Models\Master\Status;
use App\Models\Part\PartPrice;
use App\Models\Master\PartType;
use App\Models\Master\Supplier;
use App\Models\Master\Warehouse;
use App\Models\Part\PartCustomer;
use App\Models\Part\PartSupplier;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Setting\NumberingFormController;

class PartSupplierController extends Controller
{
    public $MenuID = '0301';
    public $objStock;
    public $objNumberingForm;
    public $generateType = 'PART_SUPPLIER_NUMBER';

    public function __construct()
    {
        $this->objStock = new StockController();
        $this->objNumberingForm = new NumberingFormController();
    }

    public function listPartSupplierImport(){
        $partCustomerIsSupplierList = PartCustomer::where('is_active', 1)->where('is_supplier', 1)->with('unit')->get();
        $partSupplierList = PartSupplier::where('is_active', 1)->with('unit')->get();
        $partList = $partSupplierList->merge($partCustomerIsSupplierList);

        return view('master.import.partSupplierList', [
            'partList' => $partList,
        ]);
    }

    public function listPartSupplier(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        $supplierList = Supplier::where('is_active', 1)->get();
        $divisiList = Divisi::where('is_production', 1)->where('is_active', 1)->select('divisi_id', 'divisi_name', 'part_type_id')->get();
        $partTypeList = PartType::where('is_active', 1)->get();
        $unitList = Unit::where('is_active', 1)->select('unit_id', 'unit_name')->get();
        $statusList = Status::where('is_active', 1)->where('status_type', 'supplier')->select('status_id', 'status')->get();
        $warehouseList = Warehouse::where('is_active', 1)->select('warehouse_id', 'warehouse_name')->get();

        return view('part.partSupplierTable', [
            'MenuID' => $this->MenuID,
            'supplierList' => $supplierList,
            'divisiList' => $divisiList,
            'partTypeList' => $partTypeList,
            'unitList' => $unitList,
            'statusList' => $statusList,
            'warehouseList' => $warehouseList
        ]);

    }

    public function loadPartSupplier(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $partList = PartSupplier::where('is_active', 1)->with('supplier', 'part_price', 'divisi', 'unit', 'stock_warehouse')->get();
        return['data'=> $partList];
    }

    public function addPartSupplier(Request $request){
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
            // $partExist = PartSupplier::where('supplier_id', $request->supplier_id)
            //                     ->whereRaw('TRIM(part_name) = ? ', [$partName])
            //                     ->pluck('part_name')->first();
            //                     //->where('part_name', $request->part_name)->pluck('part_name')->first();

            // if($partExist != null){
            //     return response()->json(['status' => 'Info', 'message' => 'item name already exists'], 200);
            // }

            $partNumbering = $this->objNumberingForm->GENERATE_FORM_NUMBER($this->generateType);
            $partNumber = "";
            $divisi = Divisi::where('divisi_id', $request->divisi_id)->with('partType')->get()->first();
            $supplier = Supplier::where('supplier_id', $request->supplier_id)->get()->first();
            $partNumber .= $supplier->supplier_code."-".$divisi->partType->code."".$divisi->divisi_code."-".$partNumbering;

            $partId = Uuid::uuid4()->toString();
            $part = PartSupplier::create([
                'part_supplier_id'  => $partId,
                'supplier_id'       => $request->supplier_id,
                'divisi_id'         => $request->divisi_id,
                'divisi_id'         => $request->divisi_id,
                'part_number'       => $partNumber,
                'part_name'         => $partName,
                'add_date'          => $request->add_date,
                'unit_id'           => $request->unit_id,
                'status'            => $request->status,
                'standard_packing'  => $request->standard_packing,
                'minimum_stock'     => $request->minimum_stock,
                'is_active'         => 1,
            ]);

            if ($part){
                $arrsuccess = 0;
                if($request->price){
                    for ($i=0; $i<count($request->price); $i++ ){
                        $price = PartPrice::create([
                            'part_supplier_id'  => $partId,
                            'effective_date'    => $request->effective_date[$i],
                            'price'             => $request->price[$i],
                            'is_active'         => 1,
                            'created_user'        => Auth::User()->employee->employee_name,
                        ]);

                        if($price)
                            $arrsuccess++;
                    }
                }

                if($request->stock){
                    for ($i=0; $i<count($request->stock); $i++ ){
                        $this->objStock->plusStockPartSupplier($partId, $request->warehouse_id[$i], $request->stock[$i], "Add Part Supplier");
                                $arrsuccess++;
                    }
                }

                if (($countPrice + $countStock) == $arrsuccess){
                    DB::commit();
                    return response()->json(['status' => 'Success', 'message' => 'add part success.'], 200);
                } else {
                    DB::rollback();
                    return response()->json(['status' => 'Info', 'message' => 'check the price list or stock list again.'], 200);
                }

            } else {
                DB::rollback();
                return response()->json(['status' => 'Info', 'message' => 'add part Failed.'], 200);

            }

        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }

    }

    public function updatePartSupplier(Request $request){

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

            $part = PartSupplier::find($request->part_supplier_id);
            $part->update([
                'supplier_id'       => $request->supplier_id,
                'divisi_id'         => $request->divisi_id,
                'part_name'         => $partName,
                'add_date'          => $request->add_date,
                'unit_id'           => $request->unit_id,
                'status'            => $request->status,
                'standard_packing'  => $request->standard_packing,
                'minimum_stock'     => $request->minimum_stock,
            ]);

            if ($part){

                PartPrice::where('part_supplier_id', $request->part_supplier_id)->update([
                    'is_active'         => 0,
                    'updated_user'      => Auth::User()->employee->employee_name,
                ]);

                $arrsuccess = 0;
                if($request->price){
                    for ($i=0; $i<count($request->price); $i++ ){
                        $priceUpdate = PartPrice::find($request->price_id[$i]);
                        if ($priceUpdate){
                            $priceUpdate->update([
                                'part_supplier_id'  => $request->part_supplier_id,
                                'effective_date'    => $request->effective_date[$i],
                                'price'             => $request->price[$i],
                                'is_active'         => 1,
                                'updated_user'      => Auth::User()->employee->employee_name,
                            ]);
                        } else {
                            $priceCreate = PartPrice::create([
                                'part_supplier_id'  => $request->part_supplier_id,
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
                        $this->objStock->plusStockPartSupplier($request->part_supplier_id, $request->warehouse_id[$i], $request->stock[$i], "Edit Part Supplier");
                                $arrsuccess++;
                    }
                }

                if (($countPrice + $countStock) == $arrsuccess){
                    DB::commit();
                    return response()->json(['status' => 'Success', 'message' => 'update part success.'], 200);
                } else {
                    DB::rollback();
                    return response()->json(['status' => 'Info', 'message' => 'check the price list or stock list again.'], 200);
                }

            } else {
                DB::rollback();
                return response()->json(['status' => 'Info', 'message' => 'update part failed.'], 200);
            }

        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }

    }

    public function deletePartSupplier($id)
    {

        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }

        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $part = PartSupplier::find($id);
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

    public function importPartSupplier(Request $request)
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
            // $existList = "";
            foreach($dataList as $ls){
                // $partExist = null;
                // $partExist = PartSupplier::where('supplier_id', $ls['supplier_id'])
                //                     ->where('part_name', $ls['part_name'])->pluck('part_name')->first();
                // if($partExist != null){
                //     $existList .= $partExist. ", ";
                //     continue;
                // }
                $partNumbering = $this->objNumberingForm->GENERATE_FORM_NUMBER($this->generateType);
                $partNumber = "";
                $divisi = Divisi::where('divisi_id', $ls['divisi_id'])->with('partType')->get()->first();
                $supplier = Supplier::where('supplier_id',$ls['supplier_id'])->get()->first();
                $partNumber .= $supplier->supplier_code."-".$divisi->partType->code."".$divisi->divisi_code."-".$partNumbering;

                $partId = Uuid::uuid4()->toString();
                $part = PartSupplier::create([
                    'part_supplier_id'  => $partId,
                    'supplier_id'       => $ls['supplier_id'],
                    'divisi_id'         => $ls['divisi_id'],
                    'part_number'       => $partNumber,
                    'part_name'         => $ls['part_name'],
                    'add_date'          => $ls['add_date'],
                    'unit_id'           => $ls['unit_id'],
                    'status'            => $ls['status_id'],
                    'minimum_stock'     => $ls['minimum_stock'],
                    'standard_packing'  => $ls['standard_packing'],
                    'is_active'         => 1,
                ]);

                if ($part){
                    $price = PartPrice::create([
                        'part_supplier_id'  => $partId,
                        'effective_date'    => $ls['effective_date'],
                        'price'             => $ls['price'],
                        'is_active'         => 1,
                        'created_user'      => Auth::User()->employee->employee_name,
                    ]);

                    $this->objStock->plusStockPartSupplier($partId, $ls['warehouse'], $ls['stock'],  "Add Part Supplier");
                }

            }

            // if($existList !== ""){
            //     $message .= " With error (existing part name ".$existList." )";
            // }

            DB::commit();
            return response()->json(['status' => 'Success', 'message' => $message], 200);

        }catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }
}
