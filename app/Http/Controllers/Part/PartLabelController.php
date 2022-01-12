<?php

namespace App\Http\Controllers\Part;

use PDF;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Models\Part\PartLabel;
use App\Models\Master\Supplier;
use App\Models\Part\PartSupplier;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Setting\NumberingFormController;

class PartLabelController extends Controller
{
    public $MenuID = '0305';
    public $objNumberingForm;
    public $generateType = 'F_GENERATE_LABEL';

    public function __construct()
    {
        $this->objNumberingForm = new NumberingFormController();
    }

    public function generatePartLabel(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        $supplierList = Supplier::where('is_active', 1)->get();
        $partList = PartSupplier::where('is_active', 1)->get();

        return view('part.generatePartLabelTable', [
            'MenuID'        => $this->MenuID,
            'supplierList'  => $supplierList,
            'partList'      => $partList,
        ]);

    }

    function loadPart($supplier_id){
        $dataList = PartSupplier::where('supplier_id', $supplier_id)
                        ->where('is_active', 1)
                        ->with('divisi', 'unit')
                        ->get();

        $partLabelList = $dataList->map(function($item){

            $data['part_supplier_id'] = $item->part_supplier_id;
            $data['part_number'] = $item->part_number;
            $data['part_name'] = $item->part_name;
            $data['standard_packing'] = $item->standard_packing;
            $data['unit_name'] = $item->unit->unit_name;
            $data['divisi_name'] = $item->divisi->divisi_name;
            return $data;
        });

        return['data'=> $partLabelList];
    }

    function loadPartLabel($part_supplier_id){
        $dataList = PartLabel::where('part_supplier_id', $part_supplier_id)
                        ->where('is_print', 0)
                        ->with('part_supplier', 'part_supplier.unit', 'part_supplier.divisi')
                        ->get();

        $partLabelList = $dataList->map(function($item){

            $data['part_label_id'] = $item->part_label_id;
            $data['part_label'] = $item->part_label;
            $data['part_number'] = $item->part_supplier->part_number;
            $data['part_name'] = $item->part_supplier->part_name;
            $data['standard_packing'] = $item->standard_packing;
            $data['unit_name'] = $item->part_supplier->unit->unit_name;
            $data['divisi_name'] = $item->part_supplier->divisi->divisi_name;
            return $data;
        });

        return['data'=> $partLabelList];
    }

    function getPartLabel($part_label){
        $data = PartLabel::where('part_label', strtoupper($part_label))
                        ->where('is_print', 1)
                        ->get()->first();

        return['data'=> $data];
    }

    public function addGeneratePartLabel(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        $itemList = json_decode($request->itemList, TRUE);
        DB::beginTransaction();
        try {
            if(count($itemList)){
                foreach($itemList as $ls){
                    $qty = $ls['qtyLabel'];
                    if((int)$qty >= 1){
                        for ($x = 1; $x <= $qty; $x++) {
                            $numbering = $this->objNumberingForm->GENERATE_FORM_NUMBER($this->generateType);
                            $part_label = strtoupper($ls['part_number']).$numbering;
                            PartLabel::create([
                                'part_label'            => $part_label,
                                'part_supplier_id'      => $ls['part_supplier_id'],
                                'standard_packing'      => $ls['standard_packing'],
                                'is_print'              => 0,
                                'is_checking'           => 0,
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            return response()->json(['status' => 'Success', 'message' => 'Generate label success.'], 200);
        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }

    }

    function updatePartLabelPrinted(Request $request){
        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        $itemList = json_decode($request->itemListUpdate, TRUE);
        $print_id = Uuid::uuid4()->toString();
        DB::beginTransaction();
        try {
            if(count($itemList)){
                foreach($itemList as $ls){
                    $part = PartLabel::find($ls['part_label_id']);
                    if($part){
                        $part->is_print = 1;
                        $part->print_id = $print_id;
                        $part->update();
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
}
