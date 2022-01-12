<?php

namespace App\Http\Controllers\Master;

use Throwable;
use Ramsey\Uuid\Uuid;
use App\Models\Master\PIC;
use Illuminate\Http\Request;
use App\Models\Master\Divisi;
use App\Models\Master\PicType;
use App\Models\Master\Supplier;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Master\PicController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Setting\NumberingFormController;

class SupplierController extends Controller
{
    public $MenuID = '0204';
    public $objPIC;
    public $objNumberingForm;
    public $generateType = 'SUPPLIER_CODE';

    public function __construct()
    {
        $this->objPIC = new PicController();
        $this->objNumberingForm = new NumberingFormController();
    }

    public function listSupplierImport(){
        $supplierList = Supplier::where('is_active', 1)->get();

        return view('master.import.supplierList', [
            'supplierList' => $supplierList,
        ]);
    }

    public function listSupplier(){
        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        $divisiList = Divisi::where('is_production', 1)->where('is_active', 1)->select('divisi_id', 'divisi_name')->get();
        $picTypeList = PicType::where('is_active', 1)->select('pic_type_id', 'pic_type_name')->get();

        return view('master.supplier', [
            'MenuID' => $this->MenuID,
            'divisiList' => $divisiList,
            'picTypeList' => $picTypeList,
        ]);

    }

    public function loadSupplier($is_active){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $supplierList = Supplier::where('is_active', $is_active)->orderBy('created_at', 'DESC')->get();
        return['data'=> $supplierList];
    }

    public function importSupplier(Request $request)
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
            $message = "Add supplier success.";
            foreach($dataList as $ls){
                $supplier_code = $this->objNumberingForm->GENERATE_FORM_NUMBER($this->generateType);
                $supplier_id = Uuid::uuid4()->toString();
                $supplier = Supplier::create([
                    'supplier_id'           => $supplier_id,
                    'supplier_code'         => $supplier_code,
                    'business_entity'       => $ls['business_entity'],
                    'supplier_name'         => $ls['supplier_name'],
                    'supplier_telephone'    => $ls['supplier_telephone'],
                    'supplier_fax'          => $ls['supplier_fax'],
                    'supplier_email'        => $ls['supplier_email'],
                    'supplier_address'      => $ls['supplier_address'],
                    'pay_time'              => $ls['pay_time'],
                    'bank'                  => $ls['bank'],
                    'bank_account_number'   => $ls['bank_account_number'],
                    'is_ppn'                => $ls['is_ppn'],
                    'npwp'                  => $ls['npwp'],
                    'is_active'             => 1,
                ]);
                $picList = $ls['picList'];
                foreach ($picList as $data) {
                    $pic = new PIC();
                    $pic->supplier_id     = $supplier->supplier_id;
                    $pic->pic_type_id     = $data['pic_type_id'];
                    $pic->pic_name        = $data['pic_name'];
                    $pic->pic_telephone   = $data['pic_telephone'];
                    $pic->pic_email       = $data['pic_email'];

                    $this->objPIC->addPic($pic);
                }
            }

            DB::commit();
            return response()->json(['status' => 'Success', 'message' => $message], 200);

        }catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }

    public function addSupplier(Request $request)
    {
        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        // $validator = Validator::make($request->all(), [
        //     'supplier_code' => 'required|unique:tm_supplier',
        // ]);
        // if ($validator->fails()) {
        //     return response()->json(['status' => 'Error', 'message' => 'Supplier Code ['.$request->supplier_code.'] has been taken.'], 202);
        // }

        DB::beginTransaction();
        try {
            $supplier_id = Uuid::uuid4()->toString();
            $supplier_code = $this->objNumberingForm->GENERATE_FORM_NUMBER($this->generateType);
            $supplier = Supplier::create([
                'supplier_id'           => $supplier_id,
                'supplier_code'         => $supplier_code,
                'business_entity'       => $request->business_entity,
                'supplier_name'         => $request->supplier_name,
                'supplier_telephone'    => $request->supplier_telephone,
                'supplier_fax'          => $request->supplier_fax,
                'supplier_email'        => $request->supplier_email,
                'supplier_address'      => $request->supplier_address,
                'pay_time'              => $request->pay_time,
                'bank'                  => $request->bank,
                'bank_account_number'   => $request->bank_account_number,
                'is_ppn'                => $request->is_ppn,
                'npwp'                  => $request->npwp,
                'is_active'             => 1,
            ]);

            $picList = json_decode($request->picList, TRUE);

            foreach ($picList as $ls) {
                $pic = new PIC();
                $pic->pic_id          = $ls['pic_name'];
                $pic->supplier_id     = $supplier->supplier_id;
                $pic->pic_type_id     = $ls['pic_type_id'];
                $pic->pic_name        = $ls['pic_name'];
                $pic->pic_telephone   = $ls['pic_telephone'];
                $pic->pic_email       = $ls['pic_email'];

                $this->objPIC->addPic($pic);
            }

            DB::commit();
            return response()->json(['status' => 'Success', 'message' => 'add supplier success.'], 200);

        }catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }

    }

    public function updateSupplier(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        // $validator = Validator::make($request->all(), [
        //     'supplier_code' => 'required|unique:tm_supplier,supplier_code,'.$request->supplier_id.',supplier_id',
        // ]);
        // if ($validator->fails()) {
        //     return response()->json(['status' => 'Error', 'message' => 'Supplier Code ['.$request->supplier_code.'] has been taken.'], 202);
        // }

        try {

            $supplier = Supplier::find($request->supplier_id);
            if($supplier){
                $supplier->update([
                    'supplier_code'         => $request->supplier_code,
                    'business_entity'       => $request->business_entity,
                    'supplier_name'         => $request->supplier_name,
                    'supplier_telephone'    => $request->supplier_telephone,
                    'supplier_fax'          => $request->supplier_fax,
                    'supplier_email'        => $request->supplier_email,
                    'supplier_address'      => $request->supplier_address,
                    'pay_time'              => $request->pay_time,
                    'bank'                  => $request->bank,
                    'bank_account_number'   => $request->bank_account_number,
                    'is_ppn'                => $request->is_ppn,
                    'npwp'                  => $request->npwp,
                ]);

                $picList = json_decode($request->picList, TRUE);

                $this->objPIC->deletePic('supplier_id', $supplier->supplier_id);

                foreach ($picList as $ls) {
                    $pic = new PIC();
                    $pic->pic_id          = $ls['pic_name'];
                    $pic->supplier_id     = $supplier->supplier_id;
                    $pic->pic_type_id     = $ls['pic_type_id'];
                    $pic->pic_name        = $ls['pic_name'];
                    $pic->pic_telephone   = $ls['pic_telephone'];
                    $pic->pic_email       = $ls['pic_email'];

                    $this->objPIC->addPic($pic);
                }

                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'Edit supplier success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'Supplier not found.'], 200);
            }
        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }
    }

    public function deleteSupplier($supplier_id)
    {

        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $supplier = Supplier::find($supplier_id);
            if ($supplier){
                $supplier->is_active = 0;
                $supplier->update();

                return response()->json(['status' => 'Success', 'message' => 'delete supplier success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'delete supplier failed.'], 200);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }

    public function activateSupplier($supplier_id)
    {

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $supplier = Supplier::find($supplier_id);
            if ($supplier){
                $supplier->is_active = 1;
                $supplier->update();

                return response()->json(['status' => 'Success', 'message' => 'activate supplier success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'activate supplier failed.'], 200);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }
}
