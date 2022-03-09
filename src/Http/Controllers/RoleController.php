<?php

namespace Karacraft\Roleperm\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Karacraft\Roleperm\Models\Role;
use Illuminate\Support\Facades\Session;
use Karacraft\Roleperm\Helpers\Constants;
use Karacraft\Roleperm\Models\Permission;
use Karacraft\Roleperm\Http\Requests\RoleRequest;

class RoleController extends Controller
{

    public function __construct(){
        $this->middleware(['web','auth']);  //web before auth is necessary
        // $this->middleware('role');
        // $this->middleware('role:super_admin'); // if usring Role Middleware, We are using PermissionServiceProvider
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->can('view_roles'))
            return view('roleperm::roles.index')->with('roles',Role::all()); 
        abort(403,Constants::UNAUTHORIZED_ACCESS_STRING . " to [ View Roles ]\n" . Constants::CONTACT_IT_STRING);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->can('create_role'))
            return view('roleperm::roles.create'); 
        abort(403,Constants::UNAUTHORIZED_ACCESS_STRING . " to [ Create Role ]\n" . Constants::CONTACT_IT_STRING);
    }

    public function getMasterData(Request $request)
    {
        $search = $request->search;
        $size = $request->size;
        $field = $request->sort[0]["field"];     //  Nested Array
        $dir = $request->sort[0]["dir"];         //  Nested Array

        $roles = Role::where(function ($query) use ($search) {   // Keep all where in closure to be effective
            $query->where('slug','LIKE',"%$search%")
            ->orWhere('title','LIKE',"%$search%")
            ->orWhere('description','LIKE',"%$search%");
        })
        ->orderBy($field,$dir)
        ->paginate((int) $size);
        return $roles;
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
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        $role = Role::whereTitle($request->title)->first();
        return redirect()->route('role.edit',$role->id);
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
            
            return view('roleperm::roles.edit')
            ->with('role',Role::find($id))
            ->with('models', $models)
            ->with('permissions', $permissions);
        }
        abort(403,Constants::UNAUTHORIZED_ACCESS_STRING . " to [ Edit User ]\n" . Constants::CONTACT_IT);
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
        //
    }
}
