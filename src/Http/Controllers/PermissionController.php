<?php

namespace Karacraft\RolesAndPermissions\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Karacraft\RolesAndPermissions\Models\Role;
use Karacraft\RolesAndPermissions\Models\Method;
use Karacraft\RolesAndPermissions\Models\Permission;
use Karacraft\RolesAndPermissions\Http\Requests\PermissionRequest;

class PermissionController extends Controller
{

    public function __construct(){
        $this->middleware(['web','auth']);
    }

    // https://dev.to/kingsconsult/how-to-implement-search-functionality-in-laravel-8-and-laravel-7-downwards-3g76
    public function index(Request $request)
    {
        if(auth()->user()->can('show_permission'))
        {
            $search = $request->search;
            $permissions = Permission::where(function ($query) use ($search){
                // Keep all where in closure to be effective
                $query->where('slug','LIKE',"%$search%")
                ->orWhere('title','LIKE',"%$search%")
                ->orWhere('method','LIKE',"%$search%");
            })
            ->orderBy('id','asc')
            ->paginate(config('roles-and-permissions.paging-number','paging-number'));
            return view('RolesAndPermissions::permissions.index')->with('permissions',$permissions); 
        }
        Session::flash('error',config('roles-and-permissions.unauthorized_access_string') . " to [ View Permissions ]\n");
        return redirect()->back();
    
    }

    public function create()
    {
        if(auth()->user()->can('create_permission'))
        {
            $models = config('roles-and-permissions.models','models');
            $methods = Method::all();
            return view('RolesAndPermissions::permissions.create',compact('methods','models')); 
        }
        Session::flash('error',config('roles-and-permissions.unauthorized_access_string') . " to [ Create Permissions ]\n");
        return redirect()->back();

    }

    public function store(PermissionRequest $request)
    {
        // dd($request->all());
        $methods = $request->method; // Array
        // $method = Method::findOrFail($request->method);
        DB::beginTransaction();
        try {
            foreach ($methods as $method) {
                $m = Method::findOrFail($method);
                $p = Permission::whereMethod($m->title)->whereModel($request->model)->first();
                if(!$p)
                {
                    $permission = new Permission();
                    $permission->title = $m->title . ' ' . $request->model;
                    $permission->slug = $m->title . ' ' . $request->model;
                    $permission->method = $m->title;
                    $permission->model = $request->model;
                    $permission->save();
                    $superAdminRole = Role::where('slug','super_admin')->first();
                    $superAdminRole->permissions()->attach($permission);
                    $user = User::where('email',config('roles-and-permissions.user-info.email', 'email'))->first();
                    $user->permissions()->attach($permission);
                }
            }
            DB::commit();
            Session::flash('success',"Permissions created");
            return redirect()->route('permission.index');
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function show(Permission $permission)
    {
        if(auth()->user()->can('show_permission'))
            return view('RolesAndPermissions::permissions.show',compact('permission'));
        Session::flash('error',config('roles-and-permissions.unauthorized_access_string') . " to [ View Permissions ]\n");
        return redirect()->back();
    }

    public function edit(Permission $permission)
    {
        if(auth()->user()->can('edit_permission'))
            return view('RolesAndPermissions::permissions.edit',compact('permission'));
        Session::flash('error',config('roles-and-permissions.unauthorized_access_string') . " to [ Edit Permissions ]\n");
        return redirect()->back();
    }

    public function update(Request $request, Permission $permission)
    {
        DB::beginTransaction();
        try {
            $role = Role::find($request->id);
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
            Session::flash('info',"Role [$role->title] updated");
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function destroy(Permission $permission)
    {
        $p = $permission->users()->where('permission_id',$permission->id)->exists();
        if ($p)
        {
            Session::flash('error',"Permission  [ $permission->title ] is in use");
            return redirect()->back();
        }
        $permission->delete();
        Session::flash('error',"Permission $permission->title deleted");
        return redirect()->route('permission.index');
    }
}
