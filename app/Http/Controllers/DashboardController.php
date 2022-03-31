<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Master\Plant;
use Illuminate\Http\Request;
use App\Mail\BroadcastEmailSend;
use App\Models\Part\PartCustomer;
use App\Models\Part\PartSupplier;
use App\Models\Process\Budgeting;
use App\Models\MazuMaster\Product;
use Illuminate\Support\Facades\DB;
use App\Models\MazuMaster\Customer;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use App\Models\MazuProcess\SalesOrder;
use App\Models\production\DailyReport;
use App\Models\MazuMaster\BroadcastEmail;
use App\Models\MazuMaster\ProductCategory;
use App\Models\MazuProcess\SalesOrderItem;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DashboardController extends Controller
{
    public $MenuID = '01';

    public function index()
    {
        $store_id = getStoreId();
        $productCategoryList = ProductCategory::where('store_id', $store_id)->where('is_active', 1)->get();
        $productList = Product::where('store_id', $store_id)->where('is_active', 1)->get();
        $birthdaysList = Customer::whereRaw('DAYOFYEAR(curdate()) <= DAYOFYEAR(date_of_birth) AND DAYOFYEAR(curdate()) + 7 >=  dayofyear(date_of_birth)')
                        ->orderByRaw('DAYOFYEAR(date_of_birth)')
                        ->get();

        $decRemain = SalesOrder::where('store_id', $store_id)
                    ->where('is_active', 1)
                    ->where('is_process', 1)
                    ->where('is_draft', 0)
                    ->where('is_void', 0)
                    ->sum('dec_remain');

        $today = Carbon::now(); //returns current day
        $firstDay = new Carbon('first day of this month');
        $lastDay = $today->lastOfMonth();
        $range = [$firstDay->format('Y-m-d'), $lastDay->format('Y-m-d')];
        $totalSalesOrder = SalesOrder::where('store_id', $store_id)
                        ->where('is_active', 1)
                        ->where('is_process', 1)
                        ->where('is_draft', 0)
                        ->where('is_void', 0)
                        ->whereBetween('so_date', $range)
                        ->sum('grand_total');


        return view('dashboard', [
            'MenuID'                    => $this->MenuID,
            'productCategoryList'       => $productCategoryList,
            'productList'               => $productList,
            'birthdaysList'             => $birthdaysList,
            'decRemain'                 => $decRemain,
            'totalSalesOrder'           => $totalSalesOrder,
            // 'plantList'        => $plantList
        ]);
    }
    public function loadSales($start_date, $end_date){
        $salesList = [];
        $store_id = getStoreId();
        $range = [$start_date, $end_date];
        $salesOrder = DB::table('tp_sales_order as so')
                    ->groupBy('so.so_date')
                    ->select('so.so_date', DB::raw('SUM(so.grand_total) as qty'))
                    ->whereBetween('so.so_date', $range)
                    ->where('so.store_id', $store_id)
                    ->orderBy('so.so_date', 'ASC')
                    ->get();

        if($salesOrder){
            foreach($salesOrder as $ls){

                $data['name'] = "";
                $data['date'] = $ls->so_date;
                $data['qty'] = $ls->qty;
                array_push($salesList, $data);
            }
        }

        return['data'=> $salesList];
    }

    public function loadProductSales($start_date, $end_date){
        $salesList = [];
        $store_id = getStoreId();
        $range = [$start_date, $end_date];
        $productList = Product::where('store_id', $store_id)->where('is_active', 1)->pluck('product_id');
        $salesOrder = DB::table('tp_sales_order_item as soi')
                    ->groupBy('soi.product_id', 'so.so_date')
                    ->select('soi.product_id', 'so.so_date', DB::raw('SUM(soi.qty_order) as qty'))
                    ->leftJoin('tp_sales_order as so', 'soi.so_id', '=', 'so.so_id')
                    ->whereIn('soi.product_id', $productList)
                    ->whereBetween('so.so_date', $range)
                    ->orderBy('so.so_date', 'ASC')
                    ->get();

        if($salesOrder){
            foreach($salesOrder as $ls){

                $data['name'] = Product::where('product_id', $ls->product_id)->pluck('product_name')->first();
                $data['date'] = $ls->so_date;
                $data['qty'] = $ls->qty;
                array_push($salesList, $data);
            }
        }

        return['data'=> $salesList];
    }

    public function loadProductSalesByCategory($start_date, $end_date, $product_category_id){
        $salesList = [];

        if($product_category_id === "none"){
            $range = [$start_date, $end_date];
            $salesOrder = DB::table('tp_sales_order_item as soi')
                        ->groupBy('ct.product_category_id', 'so.so_date')
                        ->select('ct.product_category_id', 'so.so_date', DB::raw('SUM(soi.qty_order) as qty'))
                        ->leftJoin('tp_sales_order as so', 'soi.so_id', '=', 'so.so_id')
                        ->leftJoin('tm_product as pr', 'soi.product_id', '=', 'pr.product_id')
                        ->leftJoin('tm_product_category as ct', 'pr.product_category_id', '=', 'ct.product_category_id')
                        ->whereBetween('so.so_date', $range)
                        ->orderBy('so.so_date', 'ASC')
                        ->get();
        }else{
            $range = [$start_date, $end_date];
            $salesOrder = DB::table('tp_sales_order_item as soi')
                        ->groupBy('ct.product_category_id', 'so.so_date')
                        ->select('ct.product_category_id', 'so.so_date', DB::raw('SUM(soi.qty_order) as qty'))
                        ->leftJoin('tp_sales_order as so', 'soi.so_id', '=', 'so.so_id')
                        ->leftJoin('tm_product as pr', 'soi.product_id', '=', 'pr.product_id')
                        ->leftJoin('tm_product_category as ct', 'pr.product_category_id', '=', 'ct.product_category_id')
                        ->where('ct.product_category_id', $product_category_id)
                        ->whereBetween('so.so_date', $range)
                        ->orderBy('so.so_date', 'ASC')
                        ->get();
        }

        if($salesOrder){
            foreach($salesOrder as $ls){

                $data['name'] = ProductCategory::where('product_category_id', $ls->product_category_id)->pluck('category_name')->first();
                $data['date'] = $ls->so_date;
                $data['qty'] = $ls->qty;
                array_push($salesList, $data);
            }
        }

        return['data'=> $salesList];
    }
    public function loadProductSalesByProduct($start_date, $end_date, $product_id){
        $salesList = [];

        $range = [$start_date, $end_date];
            $salesOrder = DB::table('tp_sales_order_item as soi')
                        ->groupBy('soi.product_id', 'so.so_date')
                        ->select('soi.product_id', 'so.so_date', DB::raw('SUM(soi.qty_order) as qty'))
                        ->leftJoin('tp_sales_order as so', 'soi.so_id', '=', 'so.so_id')
                        ->where('soi.product_id', $product_id)
                        ->whereBetween('so.so_date', $range)
                        ->orderBy('so.so_date', 'ASC')
                        ->get();

        if($salesOrder){
            foreach($salesOrder as $ls){

                $data['name'] = Product::where('product_id', $ls->product_id)->pluck('product_name')->first();
                $data['date'] = $ls->so_date;
                $data['qty'] = $ls->qty;
                array_push($salesList, $data);
            }
        }

        return['data'=> $salesList];
    }
    public function getProductName($product_id)
    {
        $name = "";
        $name = Product::where('product_id', $product_id)->pluck('product_name')->first();

        return $name;
    }

    public function loadPartSupplier(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $partList = PartSupplier::where('is_active', 1)
                                ->whereRaw('stock < minimum_stock')
                                ->with('supplier', 'divisi')->get();
        return['data'=> $partList];
    }

    function getMachineProcess($plant){

        $partCustomer = PartCustomer::where('is_active', 1)
                                    ->when($plant > 0, function ($q) use ($plant) {
                                        return $q->where('plant_id', $plant);
                                    })
                                    ->pluck('part_customer_id');


        $getDailyReport = DB::table('tp_daily_report as dr')
                    ->groupBy('pm.code', 'dr.report_date')
                    ->select('pm.code', 'dr.report_date', DB::raw('SUM(bpi.cycle_time * dri.total) as total_ct'))
                    ->leftJoin('tp_daily_report_item as dri', 'dr.report_id', '=', 'dri.report_id')
                    ->leftJoin('tm_bill_process as bp', 'dri.part_customer_id', '=', 'bp.part_customer_id')
                    ->leftJoin('tm_bill_process_item as bpi', 'bp.bill_process_id', '=', 'bpi.bill_process_id')
                    ->leftJoin('tm_process_machine as pm', 'bpi.mc', '=', 'pm.pmachine_id')
                    ->whereIn('dri.part_customer_id', $partCustomer)
                    //->whereBetween('report_date', [date('Y-m-d'), date('Y-m-d',(strtotime ( '-14 day' , strtotime (date('Y-m-d')) ) ))])
                    ->orderBy('dr.report_date', 'ASC')
                    ->get();

        return['data'=> $getDailyReport];

    }

    public function getBirthdayMail()
    {
        $birtdayMail = BroadcastEmail::where('store_id', getStoreId())
                    ->where('is_birthday', 1)
                    ->orderBy('created_at', 'DESC')->get()->first();

        return['data'=> $birtdayMail];
    }

    public function addBroadcast(Request $request)
    {
        // if(!isAccess('create', $this->MenuID)){
        //     return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        // }
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
            if($bc){
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
            }else{
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
                    'is_birthday'           => 1,
                ]);
            }
            if ($bc){
                Mail::to($request->email_birthday)->queue(new BroadcastEmailSend($bc));
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

}
