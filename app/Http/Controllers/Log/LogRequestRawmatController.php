<?php

namespace App\Http\Controllers\Log;

use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Models\Log\LogRequestRawmat;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LogRequestRawmatController extends Controller
{

    function addLogRequestRawmat($req_id, $status_process, $comment){
        LogRequestRawmat::create([
            'request_id'        => $req_id,
            'comment'           => $comment,
            'status_process'    => $status_process,
            'created_user'      => Auth::User()->employee->employee_name,
        ]);
    }

}
