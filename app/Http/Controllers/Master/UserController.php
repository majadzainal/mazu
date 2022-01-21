<?php

namespace App\Http\Controllers\Master;

use Throwable;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use App\Models\Setting\Role;
use Illuminate\Http\Request;
use App\Models\Master\Divisi;
use App\Models\Master\Employee;
use App\Models\Master\Location;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    public $MenuID = '998';

    public function listUser(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        // $divisiList = Divisi::where('is_active', 1)->select('divisi_id', 'divisi_name')->get();
        // $locationList = Location::where('is_active', 1)->select('location_id', 'location_name')->get();
        $roleList = Role::where('is_active', 1)
                        ->where('is_superuser', 0)->select('role_id', 'role_name')->get();

        return view('users.userTable', [
            'MenuID' => $this->MenuID,
            // 'divisiList' => $divisiList,
            // 'locList' => $locationList,
            'roleList' => $roleList
        ]);

    }

    public function loadUser(){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $userList = User::where('is_active', 1)->with('employee')->orderBy('created_at', 'DESC')->get();
        return['data'=> $userList];
    }

    public function addUser(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        DB::beginTransaction();
        try {
            $userId = Uuid::uuid4()->toString();
            $user = User::create([
                'user_id'           => $userId,
                'username'          => $request->username,
                'password'          => Hash::make($request->password),
                'role'              => $request->role,
                'store_id'          => getStoreId(),
                'is_superuser'      => 0,
                'is_active'         => 1,
            ]);

            if ($user){
                $idEmp = Uuid::uuid4()->toString();
                Employee::create([
                    'employee_id'          => $idEmp,
                    'user_id'              => $userId,
                    'employee_name'        => $request->name,
                    'phone'                => $request->phone,
                    'email'                => $request->email,
                    'store_id'             => getStoreId(),
                    'is_active'            => 1,
                    'created_user'         => Auth::User()->employee->employee_name,
                ]);

                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'add user success.'], 200);

            } else {
                DB::rollback();
                return response()->json(['status' => 'Info', 'message' => 'add user Failed.'], 200);

            }

        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }

    }

    public function updateUser(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:tm_user,username,'.$request->id.',user_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => errorMessage('status'), 'message' => 'Username has been taken by oher.'], errorMessage('status_number'));
        }

        DB::beginTransaction();

        try {

            $user = User::find($request->user_id);
            $user->update([
                'username'          => $request->username,
                'password'          => Hash::make($request->password),
                'role'              => $request->role,
            ]);

            if ($user){
                $employee = Employee::where('user_id', $request->user_id);
                $employee->update([
                    'employee_name'        => $request->name,
                    'phone'                => $request->phone,
                    'email'                => $request->email,
                    'updated_user'         => Auth::User()->employee->employee_name,
                ]);

                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'update user success.'], 200);

            } else {

                DB::rollback();
                return response()->json(['status' => 'Info', 'message' => 'update user failed.'], 200);

            }

        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }

    }

    public function updateProfile(Request $request){

        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:tm_user,username,'.$request->id.',user_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => errorMessage('status'), 'message' => 'Username has been taken by oher.'], errorMessage('status_number'));
        }

        DB::beginTransaction();

        try {

            $user = User::find($request->idTable);
            if ($user){
                $employee = Employee::where('user_id', $request->id);
                $employee->update([
                    'employee_name'        => $request->name,
                    'phone'                => $request->phone,
                    'email'                => $request->email,
                    'updated_user'         => Auth::User()->employee->employee_name,
                ]);

                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'update user success.'], 200);

            } else {

                DB::rollback();
                return response()->json(['status' => 'Info', 'message' => 'update user failed.'], 200);

            }

        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }

    }

    public function updatePassword(Request $request){

        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'Error', 'message' => 'Password required or password does not match.'], errorMessage('status_number'));
        }

        DB::beginTransaction();
        try {

            $user = User::find($request->user_id);
            $user->update([
                'password'          => Hash::make($request->password),
            ]);

            DB::commit();
            return response()->json(['status' => 'Success', 'message' => 'update user success.'], 200);

        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }

    public function deleteUser($id)
    {

        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        // if(isOpname()){
        //     return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        // }

        try {

            $user = User::find($id);
            if ($user){
                $user->is_active = 0;
                $user->update();

                return response()->json(['status' => 'Success', 'message' => 'delete user success.'], 200);
            } else {
                return response()->json(['status' => 'Info', 'message' => 'delete user failed.'], 200);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }
    }
}
