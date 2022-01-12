<?php

namespace App\Http\Controllers\Setting;

use Throwable;
use App\Models\Setting\Menu;
use App\Models\Setting\Role;
use Illuminate\Http\Request;
use App\Models\Setting\Menu_Role;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoleController extends Controller
{

    public $MenuID = '99902';

    public function listRole(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return redirect()->back();
        }

        $menuList = Menu::where('is_active', 1)->orderby('menu_id', 'asc')->get();

        return view('master.roleTable', [
            'MenuID' => $this->MenuID,
            'menuList' => $menuList
        ]);

    }

    public function loadRole(){

        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $roleList = Role::where('is_active', 1)->with('menu_role')->get();
        return['data'=> $roleList];
    }

    public function addRole(Request $request){

        if(!isAccess('create', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        $validator = Validator::make($request->all(), [
            'role_name' => 'required|unique:tm_role',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => errorMessage('status'), 'message' => 'Please insert role name or role name has been taken.'], errorMessage('status_number'));
        }

        DB::beginTransaction();

        try {

            $role = Role::create([
                'role_name'         => $request->role_name,
                'is_active'         => 1,
            ]);

            if ($role){
                for ($i=0; $i<count($request->menu_id); $i++ ){
                    Menu_Role::create([
                        'menu_id'          => $request->menu_id[$i],
                        'role_id'          => $role->role_id,
                        'access'           => (isset($request->get('access')[$i]) == 1)?1:0,
                        'read'             => (isset($request->get('read')[$i]) == 1)?1:0,
                        'create'           => (isset($request->get('create')[$i]) == 1)?1:0,
                        'update'           => (isset($request->get('update')[$i]) == 1)?1:0,
                        'delete'           => (isset($request->get('delete')[$i]) == 1)?1:0,
                    ]);
                }

                DB::commit();
                return response()->json(['status' => 'Success', 'message' => 'add role success.'], 200);

            } else {
                DB::rollback();
                return response()->json(['status' => 'Info', 'message' => 'add role Failed.'], 200);

            }

        } catch (ModelNotFoundException  $e) {
            //DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }

    }

    public function updateRole(Request $request){

        if(!isAccess('update', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }


        $validator = Validator::make($request->all(), [
            'role_name' => 'required|unique:tm_role,role_name,'.$request->role_id.',role_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => errorMessage('status'), 'message' => 'Please insert role name or role name has been taken.'], errorMessage('status_number'));
        }

        DB::beginTransaction();

        try {

            $role = Role::find($request->role_id);
            if ($role){
                $role->update([
                    'role_name'         => $request->role_name,
                    'is_active'         => 1,
                ]);

                $menuRole = Menu_Role::where('role_id', $request->role_id);
                $menuRole->delete();

                if ($menuRole){
                    for ($i=0; $i<count($request->menu_id); $i++ ){
                        Menu_Role::create([
                            'menu_id'          => $request->menu_id[$i],
                            'role_id'          => $role->role_id,
                            'access'           => (isset($request->get('access')[$i]) == 1)?1:0,
                            'read'             => (isset($request->get('read')[$i]) == 1)?1:0,
                            'create'           => (isset($request->get('create')[$i]) == 1)?1:0,
                            'update'           => (isset($request->get('update')[$i]) == 1)?1:0,
                            'delete'           => (isset($request->get('delete')[$i]) == 1)?1:0,
                        ]);
                    }

                    DB::commit();
                    return response()->json(['status' => 'Success', 'message' => 'edit role success.'], 200);

                }
            }

            DB::rollback();
            return response()->json(['status' => 'Info', 'message' => 'edit role Failed.'], 200);

        } catch (ModelNotFoundException  $e) {
            //DB::rollback();
            return response()->json(['status' => 'Error', 'message' => $e], 202);
        }

    }

    public function deleteRole($id)
    {
        if(!isAccess('delete', $this->MenuID)){
            return response()->json(['status' => errorMessage('status'), 'message' => errorMessage('message')], errorMessage('status_number'));
        }
        if(isOpname()){
            return response()->json(['status' => errorMessageOpname('status'), 'message' => errorMessageOpname('message')], errorMessageOpname('status_number'));
        }

        try {

            $role = Role::find($id);
            if ($role){
                $role->is_active = 0;
                $role->update();

                return response()->json(['status' => 'Success', 'message' => 'Delete role success.'], 200);
            } else {
                return response()->json(['status' => 'info', 'message' => 'Delete role failed.'], 202);
            }

        } catch (Throwable $e){
            return response()->json(['status' => 'Error', 'message' => 'Delete role failed.'], 200);
        }
    }
}
