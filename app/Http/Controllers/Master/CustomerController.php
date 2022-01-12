<?php

namespace App\Http\Controllers\Master;

use Throwable;
use Ramsey\Uuid\Uuid;
use App\Models\Master\PIC;
use Illuminate\Http\Request;
use App\Models\Master\Divisi;
use App\Models\Master\Status;
use App\Models\Master\PicType;
use App\Models\Master\Customer;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Master\PicController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Setting\NumberingFormController;

class CustomerController extends Controller
{
    public $MenuID = '0205';
    public $objPIC;
    public $objNumberingForm;
    public $generateType = 'CUSTOMER_CODE';

    public function __construct()
    {
        $this->objPIC = new PicController();
        $this->objNumberingForm = new NumberingFormController();
    }

    public function listCustomerImport(){
        $customerList = Customer::where('is_active', 1)->get();

        return view('master.import.customerList', [
            'customerList' => $customerList,
        ]);
    }

    public function listCustomer(){
        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        $picTypeList = PicType::where('is_active', 1)->select('pic_type_id', 'pic_type_name')->get();

        return view('master.customer', [
            'MenuID' => $this->MenuID,
            'picTypeList' => $picTypeList,
        ]);

    }

    public function loadCustomer($is_active){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $customerList = Customer::where('is_active', $is_active)->orderBy('created_at', 'DESC')->get();
        return['data'=> $customerList];
    }

    public function importCustomer(Request $request)
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
            $message = "Add customer success.";
            foreach($dataList as $ls){
                $customer_id = Uuid::uuid4()->toString();
                $customer_code = $this->objNumberingForm->GENERATE_FORM_NUMBER($this->generateType);
                $customer = Customer::create([
                    'customer_id'           => $customer_id,
                    'customer_code'         => $customer_code,
                    'business_entity'       => $ls['business_entity'],
                    'customer_name'         => $ls['customer_name'],
                    'customer_telephone'    => $ls['customer_telephone'],
                    'customer_fax'          => $ls['customer_fax'],
                    'customer_email'        => $ls['customer_email'],
                    'customer_address'      => $ls['customer_address'],
                    'billing_address'       => $ls['billing_address'],
                    'delivery_address'      => $ls['delivery_address'],
                    'pay_time'              => $ls['pay_time'],
                    'bank'                  => $ls['bank'],
                    'bank_account_number'   => $ls['bank_account_number'],
                    'npwp'                  => $ls['npwp'],
                    'is_active'             => 1,
                ]);
                $picList = $ls['picList'];
                foreach ($picList as $data) {
                    $pic = new PIC();
                    $pic->customer_id     = $customer->customer_id;
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

    public function addCustomer(Request $request)
    {
        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        // $validator = Validator::make($request->all(), [
        //     'customer_code' => 'required|unique:tm_customer',
        // ]);
        // if ($validator->fails()) {
        //     return response()->json(['status' => 'Error', 'message' => 'Customer Code ['.$request->customer_code.'] has been taken.'], 202);
        // }

        DB::beginTransaction();
        try {
            $customer_id = Uuid::uuid4()->toString();
            $customer_code = $this->objNumberingForm->GENERATE_FORM_NUMBER($this->generateType);
            $customer = Customer::create([
                'customer_id'           => $customer_id,
                'customer_code'         => $customer_code,
                'business_entity'       => $request->business_entity,
                'customer_name'         => $request->customer_name,
                'customer_telephone'    => $request->customer_telephone,
                'customer_fax'          => $request->customer_fax,
                'customer_email'        => $request->customer_email,
                'customer_address'      => $request->customer_address,
                'billing_address'       => $request->billing_address,
                'delivery_address'      => $request->delivery_address,
                'pay_time'              => $request->pay_time,
                'bank'                  => $request->bank,
                'bank_account_number'   => $request->bank_account_number,
                'npwp'                  => $request->npwp,
                'is_active'             => 1,
            ]);

            $picList = json_decode($request->picList, TRUE);

            foreach ($picList as $ls) {
                $pic = new PIC();
                $pic->pic_id          = $ls['pic_name'];
                $pic->customer_id     = $customer->customer_id;
                $pic->pic_type_id     = $ls['pic_type_id'];
                $pic->pic_name        = $ls['pic_name'];
                $pic->pic_telephone   = $ls['pic_telephone'];
                $pic->pic_email       = $ls['pic_email'];

                $this->objPIC->addPic($pic);
            }

            DB::commit();
            return response()->json(['status' => 'Success', 'message' => 'add customer success.'], 200);

        }catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }

    }

    public function updateCustomer(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        // $validator = Validator::make($request->all(), [
        //     'customer_code' => 'required|unique:tm_customer,customer_code,'.$request->customer_id.',customer_id',
        // ]);
        // if ($validator->fails()) {
        //     return response()->json(['status' => 'Error', 'message' => 'Customer Code ['.$request->customer_code.'] has been taken.'], 202);
        // }

        try {
            $customer = Customer::find($request->customer_id);
            if($customer){
                $customer->update([
                    'business_entity'       => $request->business_entity,
                    'customer_name'         => $request->customer_name,
                    'customer_telephone'    => $request->customer_telephone,
                    'customer_fax'          => $request->customer_fax,
                    'customer_email'        => $request->customer_email,
                    'customer_address'      => $request->customer_address,
                    'billing_address'       => $request->billing_address,
                    'delivery_address'      => $request->delivery_address,
                    'pay_time'              => $request->pay_time,
                    'bank'                  => $request->bank,
                    'bank_account_number'   => $request->bank_account_number,
                    'npwp'                  => $request->npwp,
                ]);

                $picList = json_decode($request->picList, TRUE);

                $this->objPIC->deletePic('customer_id', $customer->customer_id);

                foreach ($picList as $ls) {
                    $pic = new PIC();
                    $pic->customer_id     = $customer->customer_id;
                    $pic->pic_type_id     = $ls['pic_type_id'];
                    $pic->pic_name        = $ls['pic_name'];
                    $pic->pic_telephone   = $ls['pic_telephone'];
                    $pic->pic_email       = $ls['pic_email'];

                    $this->objPIC->addPic($pic);
                }

                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'Edit customer success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'Customer not found.'], 200);
            }
        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }
    }

    public function deleteCustomer($customer_id)
    {

        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $customer = Customer::find($customer_id);
            if ($customer){
                $customer->is_active = 0;
                $customer->update();

                return response()->json(['status' => 'Success', 'message' => 'delete customer success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'delete customer failed.'], 200);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }

    public function activateCustomer($customer_id)
    {

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $customer = Customer::find($customer_id);
            if ($customer){
                $customer->is_active = 1;
                $customer->update();

                return response()->json(['status' => 'Success', 'message' => 'delete customer success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'delete customer failed.'], 200);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }

}
