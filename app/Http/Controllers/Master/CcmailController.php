<?php

namespace App\Http\Controllers\Master;

use Throwable;
use App\Models\Master\Ccmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class CcmailController extends Controller
{

    public $MenuID = '0215';

    public function listMail(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return redirect()->back();
        }

        return view('master.ccMailTable', [
            'MenuID'       => $this->MenuID,
        ]);

    }

    public function loadMail(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $mailList = Ccmail::where('is_active', 1)->get();
        return['data'=> $mailList];
    }

    public function addMail(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }

        try {

            Ccmail::create([
                'ccmail_name'    => $request->name,
                'ccmail_email'   => $request->email,
                'is_active'      => 1,
            ]);

            return response()->json(['status' => 'Success', 'message' => 'Add Email success.'], 200);

        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function updateMail(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }

        try {

            $mail = Ccmail::find($request->ccmail_id);
            if($mail){
                $mail->update([
                    'ccmail_name'    => $request->name,
                    'ccmail_email'   => $request->email,
                ]);

                return response()->json(['status' => 'Success', 'message' => 'Edit Email success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'Email not found.'], 200);
            }
        } catch (ModelNotFoundException  $e) {
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }

    }

    public function deleteMail($id)
    {
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }

        try {

            $mail = Ccmail::find($id);
            if ($mail){
                $mail->is_active = 0;
                $mail->update();

                return response()->json(['status' => 'Success', 'message' => 'Delete email success.'], 200);
            } else {
                return response()->json(['status' => 'info', 'message' => 'Delete email failed.'], 202);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e ], 202);
        }
    }
}
