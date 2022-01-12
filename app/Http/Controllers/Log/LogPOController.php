<?php

namespace App\Http\Controllers\Log;

use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Models\Log\LogPO;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LogPOController extends Controller
{

    function addLogPO($po_id, $status_process, $comment){
        LogPO::create([
            'po_id'             => $po_id,
            'comment'           => $comment,
            'status_process'    => $status_process,
            'created_user'      => Auth::User()->employee->employee_name,
        ]);
    }

}
