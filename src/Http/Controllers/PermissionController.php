<?php

namespace Karacraft\RolesAndPermissions\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Karacraft\RolesAndPermissions\Models\Method;
use Karacraft\RolesAndPermissions\Models\Permission;
use Karacraft\RolesAndPermissions\Http\Requests\PermissionRequest;

class PermissionController extends Controller
{

    public function __construct(){
        $this->middleware(['web','auth']);
    }

    public function index()
    {
        if(auth()->user()->can('show_permission'))
            return view('RolesAndPermissions::permissions.index')->with('permissions',Permission::paginate(config('roles-and-permissions.paging-number','paging-number'))); 
        abort(403,config('roles-and-permissions.unauthorized_access_string') . " to [ View Permissions ]\n");
    }

    public function create()
    {
        if(auth()->user()->can('create_permission'))
        {
            $models = config('roles-and-permissions.models','models');
            // dd($models);
            $methods = Method::all();
            return view('RolesAndPermissions::permissions.create',compact('methods','models')); 
        }
        abort(403,config('roles-and-permissions.unauthorized_access_string') . " to [ Create Permission ]\n");
    }

    public function store(PermissionRequest $request)
    {
        DB::beginTransaction();
        try {
            $permission = new Permisson();
            $permission->title = $request->title;
            $permission->slug = $request->title;
            $permission->save();
            DB::commit();
            Session::flash('success',"Permission $permission->title is created");
            // TODO: Every new permission, should be given to Super Admin
            return redirect()->route('Permission.index');
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function show(Permission $permission)
    {
        if(auth()->user()->can('show_permission'))
            return view('RolesAndPermissions::permissions.show',compact('permission'));
        abort(403,config('roles-and-permissions.unauthorized_access_string') . " to [ View Permission ]\n");
    }

    public function edit(Permission $permission)
    {
        if(auth()->user()->can('edit_permission'))
            return view('RolesAndPermissions::permissions.edit',compact('permission'));
        abort(403,config('roles-and-permissions.unauthorized_access_string') . " to [ Edit Role ]\n");
    }

    public function update(RoleRequest $request, Permission $permission)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $role = Role::find($request->id);
            // $role->title = $request->title;
            $role->description = $request->description;
            $role->slug = $request->title;
            $role->save();
            // Update all user permissions, and set according to Role
            if ($request->has('permissions'))
            {
                $role->permissions()->sync($request->permissions);
                foreach($role->users as $user)
                {
                    $roles = $user->roles;
                    $user->roles()->sync($role);
                    $user->permissions()->sync($request->permissions);
                    // dd($roles);
                }
            }
            DB::commit();
            Session::flash('success',"Role [$role->title] updated");
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        return redirect()->back();
    }

    public function destroy(Permission $permission)
    {
        $p = $permission->users()->where('permission_id',$permission->id)->exists();
        if ($p)
            abort(403,"Permission  [ $permission->title ] is in use");
        $permission->delete();
        return redirect()->route('permiss$permission.index');
    }
}
