<?php

namespace Karacraft\RolesAndPermissions\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Karacraft\RolesAndPermissions\Models\Role;
use Karacraft\RolesAndPermissions\Models\Permission;
use Karacraft\RolesAndPermissions\Http\Requests\RoleRequest;

class RoleController extends Controller
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
        if(auth()->user()->can('show_role'))
            return view('RolesAndPermissions::roles.index')->with('roles',Role::paginate(5)); 
        abort(403,$this->UNAUTHORIZED_ACCESS_STRING . " to [ View Roles ]\n");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->can('create_role'))
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
            $role = new Role();
            $role->title = $request->title;
            $role->description = $request->description;
            $role->slug = $request->title;
            $role->save();
            DB::commit();
            Session::flash('success',"Role $role->title is created");
            return redirect()->route('role.edit',$role->id);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(auth()->user()->can('edit_role'))
        {
            $permissions = Permission::all();
            $models = Permission::select('model')->distinct()->get();    
            
            return view('RolesAndPermissions::roles.edit')
            ->with('role',Role::find($id))
            ->with('models', $models)
            ->with('permissions', $permissions);
        }
        abort(403,UNAUTHORIZED_ACCESS_STRING . " to [ Edit User ]\n" . CONTACT_IT);
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
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
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
