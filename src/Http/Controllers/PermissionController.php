<?php

namespace Karacraft\RolesAndPermissions\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Karacraft\RolesAndPermissions\Models\Permission;
use Karacraft\RolesAndPermissions\Http\Requests\RoleRequest;

class PermissionController extends Controller
{

    private $UNAUTHORIZED_ACCESS_STRING = 'Unauthorzied Access';
    public function __construct(){
        $this->middleware(['web','auth']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->can('show_permission'))
            return view('RolesAndPermissions::permissions.index')->with('permissions',Permission::paginate(5));  
        abort(403,$this->UNAUTHORIZED_ACCESS_STRING . " to [ View Permissions ]\n");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->can('create_permission'))
            return view('RolesAndPermissions::roles.create'); 
        abort(403,UNAUTHORIZED_ACCESS_STRING . " to [ Create Role ]\n");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        DB::beginTransaction();
        try {
            $permission = new Permisson();
            $permission->title = $request->title;
            $permission->slug = $request->title;
            $permission->save();
            DB::commit();
            Session::flash('success',"Permission $permission->title is created");
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        $permission = Permisson::whereTitle($request->title)->first();
        return redirect()->route('Permission.edit',$permission->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Permisson $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Permisson  $role
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // if(auth()->user()->can('edit_permission'))
        // {
        //     $permissions = Permission::all();
        //     $models = Permission::select('model')->distinct()->get();    
            
        //     return view('RolesAndPermissions::roles.edit')
        //     ->with('role',Role::find($id))
        //     ->with('models', $models)
        //     ->with('permissions', $permissions);
        // }
        // abort(403,UNAUTHORIZED_ACCESS_STRING . " to [ Edit User ]\n" . CONTACT_IT);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, Role $role)
    {
        if($request->id == 1)
            abort(403,'You cannot edit Super Admin');
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('role.index');
    }
}
