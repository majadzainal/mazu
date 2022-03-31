<?php

namespace App\Http\Controllers\MazuMaster;

use Throwable;
use Illuminate\Http\Request;
use App\Mail\BroadcastEmailSend;
use Illuminate\Support\Facades\DB;
use App\Models\MazuMaster\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\MazuMaster\BroadcastEmail;
use App\Models\MazuMaster\ExclusiveReseller;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BroadcastEmailController extends Controller implements ShouldQueue
{
    public $MenuID = '996';

    public function emailTable(){
        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        return view('mazumaster.bcEmailTable', [
            'MenuID' => $this->MenuID,
        ]);

    }

    public function loadBroadcast(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $bcList = BroadcastEmail::where('store_id', getStoreId())->where('is_birthday', 0)->orderBy('created_at', 'DESC')->get();
        return['data'=> $bcList];
    }

    public function loadCustomer(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $custList = $this->justgetCustomer();
        return['data'=> $custList];
    }

    public function justgetCustomer(){
        $custList = [];
        $store_id = getStoreId();
        $customer = Customer::where('store_id', $store_id)->where('is_active', 1)->select('customer_name', 'email')->get();
        $excReseller = ExclusiveReseller::where('store_id', $store_id)->where('is_active', 1)->select('reseller_name', 'email')->get();

        if($customer){
            foreach($customer as $ls){
                $data['name'] = $ls->customer_name;
                $data['email'] = $ls->email;
                array_push($custList, $data);
            }
        }

        if($excReseller){
            foreach($excReseller as $ls){
                $data['name'] = $ls->reseller_name;
                $data['email'] = $ls->email;
                array_push($custList, $data);
            }
        }


        return $custList;
    }

    public function addBroadcast(Request $request)
    {
        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        // dd($request);
        DB::beginTransaction();
        try {

            $file_name = "";
            if($request->banner != ""){
                // if($request->images->before)
                $file = $request->file("banner");
                $file_name = time().".".$file->getClientOriginalExtension();
                $img = Image::make($file);
                $img->save(public_path('uploads/'.$file_name));
            }


            $bc = BroadcastEmail::create([
                'subject'               => $request->subject,
                'header_text'           => $request->header_text,
                'opening_text'          => $request->opening_text,
                'content_text'          => $request->content_text,
                'regards_text'          => $request->regards_text,
                'regards_value_text'    => $request->regards_value_text,
                'footer_text'           => $request->footer_text,
                'banner'                => $file_name,
                'store_id'              => getStoreId(),
                'is_active'             => 1,
                'is_birthday'           => 0,
            ]);

            if ($bc){
                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'add broadcast email success.'], 200);
            } else {
                DB::rollback();
                return response()->json(['status' => 'Info', 'message' => 'add broadcast email failed.'], 200);
            }

        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }

    public function updateBroadcast(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }
        // dd($request);
        DB::beginTransaction();
        try {
            $file_name = "";
            if($request->banner != ""){
                if($request->banner_before != ""){
                    $file_name = $request->banner_before;
                    $image_path = public_path('uploads/'.$file_name);  // Value is not URL but directory file path
                    if(File::exists($image_path)) {
                        File::delete($image_path);
                    }
                }
                $file = $request->file("banner");
                $file_name = time().".".$file->getClientOriginalExtension();
                $img = Image::make($file);
                $img->save(public_path('uploads/'.$file_name));
            }else{
                $file_name = $request->banner_before;
            }

            $bc = BroadcastEmail::find($request->broadcast_email_id);
            $bc->update([
                'subject'               => $request->subject,
                'header_text'           => $request->header_text,
                'opening_text'          => $request->opening_text,
                'content_text'          => $request->content_text,
                'regards_text'          => $request->regards_text,
                'regards_value_text'    => $request->regards_value_text,
                'footer_text'           => $request->footer_text,
                'banner'                => $file_name,
            ]);

            if ($bc){
                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'edit broadcast email success.'], 200);
            } else {
                DB::rollback();
                return response()->json(['status' => 'Info', 'message' => 'edit broadcast email failed.'], 200);
            }

        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }

    }

    public function deleteBroadcast($id)
    {

        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            $bc = BroadcastEmail::find($id);
            if ($bc){
                $bc->is_active = 0;
                $bc->update();

                return response()->json(['status' => 'Success', 'message' => 'delete broadcast email success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'delete broadcast email failed.'], 200);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }

    public function doBroadcast(Request $request)
    {
        try {
            $bc = BroadcastEmail::where('broadcast_email_id', $request->broadcast_email_id_send)->get()->first();
            $emailList = json_decode($request->email_list, TRUE);
            // dd($emailList);
            if(count($emailList)){
                foreach($emailList as $ls){
                    $email = $ls['email'];
                    if($email !== ""){
                        Mail::to($email)->queue(new BroadcastEmailSend($bc));
                    }

                }
                if (Mail::failures()) {
                    return response()->json(['status' => 'Info', 'message' => 'send broadcast email failed.'], 200);
                } else {
                    return response()->json(['status' => 'Success', 'message' => 'send broadcast email success.'], 200);
                }
            }
        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }
}
