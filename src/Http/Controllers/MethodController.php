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

    public function index(Request $request)
    {
        if(auth()->user()->can('show_method'))
        {
            $search = $request->search;
            $methods = Method::where(function ($query) use ($search){
                // Keep all where in closure to be effective
                $query->where('slug','LIKE',"%$search%")
                ->orWhere('title','LIKE',"%$search%");
            })
            ->orderBy('id','asc')
            ->paginate(config('roles-and-permissions.paging-number','paging-number'));
            return view('RolesAndPermissions::methods.index')->with('methods',$methods); 
        }
        Session::flash('error',config('roles-and-permissions.unauthorized_access_string') . " to [ View Methods ]\n");
        return redirect()->back();
    }

    public function create()
    {
        if(auth()->user()->can('create_method'))
            return view('RolesAndPermissions::methods.create'); 
        Session::flash('error',config('roles-and-permissions.unauthorized_access_string') . " to [ Create Methods ]\n");
        return redirect()->back();
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
        Session::flash('error',config('roles-and-permissions.unauthorized_access_string') . " to [ View Methods ]\n");
        return redirect()->back();
    }

    public function edit(Method $method)
    {
        if(auth()->user()->can('edit_method'))
            return view('RolesAndPermissions::methods.edit',compact('method'));
        Session::flash('error',config('roles-and-permissions.unauthorized_access_string') . " to [ Edit Methods ]\n");
        return redirect()->back();
    }

    public function update(MethodRequest $request, Method $method)
    {   
        $permission = Permission::where('method',$method->title)->first();
        if($permission)
        {
            Session::flash('error',"Permission dependent on Method [ $method->title ] exists. Delete the permission to edit the method");
            return redirect()->back();
        }

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
        {
            Session::flash('error',"Method $method->title deleted");
            return redirect()->back();
        }
        $method->delete();
        Session::flash('error',"Method $method->title deleted");
        return redirect()->route('method.index');
    }
}
