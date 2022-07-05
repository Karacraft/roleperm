<?php

namespace Karacraft\RolesAndPermissions\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Karacraft\RolesAndPermissions\Models\Method;
use Karacraft\RolesAndPermissions\Models\Permission;
use Karacraft\RolesAndPermissions\Http\Requests\MethodRequest;

class MethodController extends Controller
{

    public function __construct(){
        $this->middleware(['web','auth']);
    }

    public function index()
    {
        if(auth()->user()->can('show_method'))
            return view('RolesAndPermissions::methods.index')->with('methods',Method::paginate(config('roles-and-permissions.paging-number','paging-number'))); 
        abort(403,config('roles-and-permissions.unauthorized_access_string') . " to [ View Methods ]\n");
    }

    public function create()
    {
        if(auth()->user()->can('create_method'))
            return view('RolesAndPermissions::methods.create'); 
        abort(403,config('roles-and-permissions.unauthorized_access_string') . " to [ Create Method ]\n");
    }

    public function store(MethodRequest $request)
    {
        DB::beginTransaction();
        try {
            $method = new Method();
            $method->title = $request->title;
            $method->slug = $request->title;
            $method->save();
            DB::commit();
            Session::flash('success',"Method $method->title is created");
            return redirect()->route('method.index');
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function show(Method $method)
    {
        if(auth()->user()->can('show_method'))
            return view('RolesAndPermissions::methods.show',compact('method'));
        abort(403,config('roles-and-permissions.unauthorized_access_string') . " to [ View Method ]\n");
    }

    public function edit(Method $method)
    {
        if(auth()->user()->can('edit_method'))
            return view('RolesAndPermissions::methods.edit',compact('method'));
        abort(403,config('roles-and-permissions.unauthorized_access_string') . " to [ Edit Method ]\n");
    }

    public function update(MethodRequest $request, Method $method)
    {   
        $permission = Permission::where('method',$method->title)->first();
        if($permission)
            abort(403,"Permission dependent on Method [ $method->title ] exists. Delete the permission to edit the method");

        DB::beginTransaction();
        try {
            $method->title = $request->title;
            $method->slug = $request->title;
            $method->save();
            DB::commit();
            Session::flash('info',"Method $method->title is updated");
            return redirect()->route('method.index');
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function destroy(Method $method)
    {
        $permission = Permission::where('method',$method->title)->first();
        if($permission)
            abort(403,"Permission dependent on Method [ $method->title ] exists. Unable to delete");
        $method->delete();
        Session::flash('error',"Method $method->title deleted");
        return redirect()->route('method.index');
    }
}
