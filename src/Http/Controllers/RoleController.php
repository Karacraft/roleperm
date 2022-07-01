<?php

namespace Karacraft\RolesAndPermissions\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Karacraft\RolesAndPermissions\Models\Role;
use Karacraft\RolesAndPermissions\Http\Requests\RoleRequest;

class RoleController extends Controller
{

    public function __construct(){
        $this->middleware(['web','auth']);
    }

    public function index()
    {
        if(auth()->user()->can('show_role'))
            return view('RolesAndPermissions::roles.index')->with('roles',Role::paginate(config('roles-and-permissions.paging-number','paging-number'))); 
        abort(403,config('roles-and-permissions.unauthorized_access_string') . " to [ View Roles ]\n");
    }

    public function create()
    {
        if(auth()->user()->can('create_role'))
            return view('RolesAndPermissions::roles.create'); 
        abort(403,config('roles-and-permissions.unauthorized_access_string') . " to [ Create Role ]\n");
    }

    public function store(RoleRequest $request)
    {
        DB::beginTransaction();
        try {
            $role = new Role();
            $role->title = $request->title;
            $role->description = $request->description;
            $role->slug = $request->title;
            $role->save();
            DB::commit();
            Session::flash('success',"Role $role->title is created");
            return redirect()->route('role.index');
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function show(Role $role)
    {
        if(auth()->user()->can('show_role'))
            return view('RolesAndPermissions::roles.show',compact('role'));
        abort(403,config('roles-and-permissions.unauthorized_access_string') . " to [ View Role ]\n");
    }

    public function edit(Role $role)
    {
        if(auth()->user()->can('edit_role'))
        // TODO: Send Permissions attached to this role as well as new one,so that user can add these as well
            return view('RolesAndPermissions::roles.edit',compact('role'));
        abort(403,config('roles-and-permissions.unauthorized_access_string') . " to [ Edit Role ]\n");
    }

    public function update(RoleRequest $request, Role $role)
    {
        if($request->slug == 'super_admin')
            abort(403,'You cannot edit Super Admin');

        DB::beginTransaction();
        try {
            $role->description = $request->description;
            $role->slug = $request->title;
            $role->save();
            // Update all user permissions, and set according to Role
            //  TODO: Update Permissions
            // if ($request->has('permissions'))
            // {
            //     $role->permissions()->sync($request->permissions);
            //     foreach($role->users as $user)
            //     {
            //         $roles = $user->roles;
            //         $user->roles()->sync($role);
            //         $user->permissions()->sync($request->permissions);
            //     }
            // }
            DB::commit();
            Session::flash('success',"Role [$role->title] updated");
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('role.index');
    }
}
