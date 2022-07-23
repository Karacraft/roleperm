<?php

namespace Karacraft\RolesAndPermissions\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Karacraft\RolesAndPermissions\Models\Role;
use Karacraft\RolesAndPermissions\Models\Method;
use Karacraft\RolesAndPermissions\Models\Permission;
use Karacraft\RolesAndPermissions\Http\Requests\RoleRequest;

class RoleController extends Controller
{

    public function __construct(){
        $this->middleware(['web','auth']);
    }

    public function index(Request $request)
    {
        if(auth()->user()->can('show_role'))
        {
            $search = $request->search;
            $roles = Role::where(function ($query) use ($search){
                // Keep all where in closure to be effective
                $query->where('slug','LIKE',"%$search%")
                ->orWhere('title','LIKE',"%$search%");
            })
            ->orderBy('id','asc')
            ->paginate(config('roles-and-permissions.paging-number','paging-number'));
            return view('RolesAndPermissions::roles.index')->with('roles',$roles); 
        }
        Session::flash('error',config('roles-and-permissions.unauthorized_access_string') . " to [ View Roles ]\n");
        return redirect()->back();
    }

    public function create()
    {
        if(auth()->user()->can('create_role'))
            return view('RolesAndPermissions::roles.create'); 
        Session::flash('error',config('roles-and-permissions.unauthorized_access_string') . " to [ Create Role ]\n");
        return redirect()->back();
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
        Session::flash('error',config('roles-and-permissions.unauthorized_access_string') . " to [ View Role ]\n");
        return redirect()->back();
    }

    public function edit(Role $role)
    {
        if(auth()->user()->can('edit_role'))
        {
            $permissions = Permission::all();
            $models = $permissions->unique('model')->pluck('model');
            $rolePermissions = $role->permissions;
            // dd($rolePermissions->search('15',true));
            return view('RolesAndPermissions::roles.edit',compact('role','permissions','models','rolePermissions'));
        }
        // abort(403,config('roles-and-permissions.unauthorized_access_string') . " to [ Edit Role ]\n");
        Session::flash('error',config('roles-and-permissions.unauthorized_access_string') . " to [ Edit Role ]\n");
        return redirect()->back();
        
    }

    public function update(RoleRequest $request, Role $role)
    {
        // dd($request->all());
        if($role->slug == 'super_admin')
        {
            Session::flash('error','You cannot edit Super Admin');
            return redirect()->back();
        }
        
        if($request->has('updatePermissions'))
        {
            DB::beginTransaction();
            try {
                $role->permissions()->sync($request->permissions);
                foreach($role->users as $user)
                {
                    $roles = $user->roles;
                    $user->roles()->sync($role);
                    $user->permissions()->sync($request->permissions);
                }
                DB::commit();
                Session::flash('info',"Permissions for Role [$role->title] updated");
                return redirect()->back();
            } catch (\Throwable $th) {
                DB::rollback();
                throw $th;
            }
        }
        //  The Role Part
   
            
        if(count($role->permissions->all()) == 0)
        {
            Session::flash('error',"[ $role->title ] has permissions attached, remove them first before editing");
            return redirect()->back();
        }
      
        DB::beginTransaction();
        try {
            $role->title = $request->title;
            $role->description = $request->description;
            $role->slug = $request->title;
            $role->save();
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
        if($role->slug == 'super_admin')
        {
            Session::flash('error','You cannot delete Super Admin');
            return redirect()->back();
        }

        $role->delete();
        Session::flash('error',"Role [$role->title] deleted");
        return redirect()->route('role.index');
    }
}
