<?php

namespace App\Http\Controllers\Setting;

use DateTime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Setting\Counter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting\NumberingForm;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class NumberingFormController extends Controller
{
    public $MenuID = '99901';

    public function listNumberingForm(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }
        $counterList = Counter::where('is_active', 1)->select('counter_id', 'counter_name', 'counter', 'length')->get();
        $numFormList = NumberingForm::all();

        return view('setting.numberingForm', [
            'MenuID' => $this->MenuID,
            'counterList'   => $counterList,
            'numFormList'   => $numFormList,
        ]);

    }

    public function loadNumberingForm(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $numberingFormList = NumberingForm::select('numbering_form_id', 'numbering_form_type', 'numbering_form_name')->get();
        return['data'=> $numberingFormList];
    }

    public function getNumberingForm($numbering_form_id){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $numberingForm = NumberingForm::where('numbering_form_id', $numbering_form_id)->get()->first();
        return['data'=> $numberingForm];
    }

    public function updateNumberingForm(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $numForm = NumberingForm::find($request->numbering_form_id);
            if($numForm){
                $numForm->update([
                    'numbering_form_name'       => $request->numbering_form_name,
                    'string_val'                => $request->string_val,
                    'string_used'               => $request->string_used,
                    'year_val'                  => $request->year_val,
                    'year_used'                 => $request->year_used,
                    'month_used'                => $request->month_used,
                    'day_used'                  => $request->day_used,
                    'counter_id'                => $request->counter_id_form,
                    'updated_user'              => Auth::User()->employee->employee_name,

                ]);

                return response()->json(['status' => 'Success', 'message' => 'Edit numbering form success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'Numbering form not found.'], 200);
            }
        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    function GENERATE_FORM_NUMBER($numbering_form_type){
        $generateNumber = "";
        $numForm = NumberingForm::where('numbering_form_type', $numbering_form_type)->with('counter')->get()->first();
        $now = new DateTime('today');

        $generateNumber .= $numForm->string_used == 1 ? $numForm->string_val : '';

        $generateNumber .= $numForm->year_used == 1 ? $numForm->year_val === 2 ? $now->format('y') : $now->format('Y') : '';

        $generateNumber .= $numForm->month_used == 1 ? $now->format('m') : '';

        $generateNumber .= $numForm->day_used == 1 ? $now->format('d') : '';

        $counterStr = $this->getCounter($numForm->counter);

        $generateNumber .= $counterStr;

        return $generateNumber;
    }

    function getCounter(Counter $counter){
        $nextCounter = $counter->counter + 1;
        $counterStr = '';
        $maxCounter = '';
        $length = ($counter->length) - (strlen((string)$nextCounter));

        for($j = 1; $j <= $counter->length; $j++){$maxCounter .= '9';}

        if($nextCounter > $maxCounter){$nextCounter = 1;}

        for($i = 1; $i <= $length; $i++){$counterStr .= '0';}

        $counterStr .= (string)$nextCounter;

        // dd($counterStr);

        $counter->counter = $nextCounter;
        $counter->update();


        return $counterStr;
    }
}
