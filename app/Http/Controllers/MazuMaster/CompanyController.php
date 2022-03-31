<?php

namespace App\Http\Controllers\MazuMaster;

use Exception;
use Illuminate\Http\Request;
use App\Models\MazuMaster\Company;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use RealRashid\SweetAlert\Facades\Alert;

class CompanyController extends Controller
{
    public $MenuID = '013';

    public function company(){
        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        return view('mazumaster.companyTable', [
            'MenuID' => $this->MenuID,
        ]);

    }

    public function loadCompany(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }
        $store_id = getStoreId();
        $company = Company::where('store_id', $store_id)->get()->first();

        return['data'=> $company];
    }

    public function updateCompany(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        // dd($request);
        DB::beginTransaction();
        try {

            $file_name_logo = "";
            $file_name_banner_invoice = "";

            if($request->logo != ""){
                if($request->logo_before != ""){
                    $file_name_logo = $request->logo_before;
                    $image_path = public_path('uploads/'.$file_name_logo);  // Value is not URL but directory file path
                    if(File::exists($image_path)) {
                        File::delete($image_path);
                    }
                }
                $file = $request->file("logo");
                $file_name_logo = "company_logo_".time().".".$file->getClientOriginalExtension();
                $img = Image::make($file);
                $img->save(public_path('uploads/'.$file_name_logo));
            }else{
                $file_name_logo = $request->logo_before;
            }

            if($request->banner_invoice != ""){
                if($request->banner_invoice_before != ""){
                    $file_name_banner_invoice = $request->banner_invoice_before;
                    $image_path = public_path('uploads/'.$file_name_banner_invoice);  // Value is not URL but directory file path
                    if(File::exists($image_path)) {
                        File::delete($image_path);
                    }
                }
                $file = $request->file("banner_invoice");
                $file_name_banner_invoice = "company_banner_".time().".".$file->getClientOriginalExtension();
                $img = Image::make($file);
                $img->save(public_path('uploads/'.$file_name_banner_invoice));
            }else{
                $file_name_banner_invoice = $request->banner_invoice_before;
            }

            $store_id = getStoreId();
            $company = Company::where('store_id', $store_id)->get()->first();
            if($company){
                $company->update([
                    'instagram'             => $request->instagram,
                    'facebook'              => $request->facebook,
                    'youtube'               => $request->youtube,
                    'email'                 => $request->email_company,
                    'whatsapp'              => $request->whatsapp,
                    'website'               => $request->website,
                    'address'               => $request->address,
                    'logo'                  => $file_name_logo,
                    'banner_invoice'        => $file_name_banner_invoice,
                    'store_id'              => getStoreId(),
                ]);
            }else{
                $company = Company::create([
                    'instagram'             => $request->instagram,
                    'facebook'              => $request->facebook,
                    'youtube'               => $request->youtube,
                    'email'                 => $request->email_company,
                    'whatsapp'              => $request->whatsapp,
                    'website'               => $request->website,
                    'address'               => $request->address,
                    'logo'                  => $file_name_logo,
                    'banner_invoice'        => $file_name_banner_invoice,
                    'store_id'              => getStoreId(),
                ]);
            }
            if($company){
                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'Update company data success.'], 200);
            } else {
                DB::rollback();
                return response()->json(['status' => 'Info', 'message' => 'Update company data failed.'], 200);
            }

        } catch (Exception  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 202);
        }
    }

}
