<?php

namespace App\Http\Controllers\MazuMaster;

use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Models\MazuMaster\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\MazuMaster\LabelProduct;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Setting\NumberingFormController;

class LabelProductController extends Controller
{
    public $MenuID = '091';
    public $objNumberingForm;
    public $generateType = 'F_GENERATE_LABEL_PRODUCT';

    public function __construct()
    {
        $this->objNumberingForm = new NumberingFormController();
    }

    public function generateLabelProduct(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        $productList = Product::where('is_active', 1)
                    ->where('store_id', getStoreId())
                    ->with('category')->get();

        return view('mazumaster.generateLabelProductTable', [
            'MenuID'        => $this->MenuID,
            'productList'  => $productList,
        ]);

    }

    function loadLabel($product_id){
        $dataList = LabelProduct::where('product_id', $product_id)
                        ->where('is_print', 0)
                        ->where('store_id', getStoreId())
                        ->with('product', 'product.unit', 'product.category')
                        ->get();

        return['data'=> $dataList];
    }

    public function addGenerateLabel(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        DB::beginTransaction();
        try {
            $qty = $request->qty_generate;
            if((int)$qty >= 1){
                for ($x = 1; $x <= $qty; $x++) {
                    $numbering = $this->objNumberingForm->GENERATE_FORM_NUMBER($this->generateType);
                    $label = $numbering;
                    LabelProduct::create([
                        'no_label'              => $label,
                        'product_id'            => $request->product_id,
                        'is_print'              => 0,
                        'is_checked_in'         => 0,
                        'is_checked_out'        => 0,
                        'store_id'              => getStoreId(),
                        'created_user'          => Auth::User()->employee->employee_name,
                    ]);
                }
            }
            DB::commit();
            return response()->json(['status' => 'Success', 'message' => 'Generate label success.'], 200);
        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }

    }
    public function updateGenerateLabel(Request $request)
    {
        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        $itemList = json_decode($request->itemListUpdate, TRUE);
        DB::beginTransaction();
        try {
            if(count($itemList)){
                $print_id = Uuid::uuid4()->toString();
                foreach($itemList as $ls){
                    $label = LabelProduct::find($ls['label_product_id']);
                    if($label){
                        $label->print_id = $print_id;
                        $label->is_print = 1;
                        $label->update();
                    }
                }
            }

            DB::commit();
            return response()->json(['status' => 'Success',
                        'message' => 'Print label success.',
                        'print_id' => $print_id], 200);
        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }

    public function printLabel($print_id){

        if(!isAccess('read', $this->MenuID)){
            return "You do not have access for this action";
        }

        $labelList = LabelProduct::with('product', 'product.category')
                            ->where('print_id', $print_id)->get();
        // dd($labelList);
        if($labelList){
            $dataList = ['dataList'  => $labelList];
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('mazumaster.print.printLabel', $dataList);
            // $pdf->setPaper('A4', 'potrait');
            $pdf->setPaper([0, 0, 288, 432], 'potrait');
            return $pdf->stream('PrintLabel.pdf');
        } else {
            return "Data not found";
        }

    }
}
