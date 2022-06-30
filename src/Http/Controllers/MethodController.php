<?php

namespace Karacraft\RolesAndPermissions\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Karacraft\RolesAndPermissions\Models\Method;

class MethodController extends Controller
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
        if(auth()->user()->can('show_method'))
            return view('RolesAndPermissions::methods.index')->with('methods',Method::paginate(5));  
        abort(403,$this->UNAUTHORIZED_ACCESS_STRING . " to [ View Methods ]\n");
    }

    public function create()
    {
        if(auth()->user()->can('create_method'))
            return view('RolesAndPermissions::methods.create'); 
        abort(403,UNAUTHORIZED_ACCESS_STRING . " to [ Create Method ]\n");
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $method = new Method();
            $method->title = $request->title;
            $method->slug = $request->title;
            $method->save();
            DB::commit();
            Session::flash('success',"Method $method->title is created");
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        $method = Method::whereTitle($request->title)->first();
        return redirect()->route('Permission.edit',$method->id);
    }

    public function show(Method $method)
    {
        //
    }

    public function edit(Method $method)
    {

    }

    public function update(RoleRequest $request, Method $method)
    {
        if($request->id == 1)
            abort(403,'You cannot edit Super Admin');
        // dd($request->all());
        DB::beginTransaction();
        try {
            $method = Method::find($request->id);
            // $method->title = $request->title;
            $method->description = $request->description;
            $method->slug = $request->title;
            $method->save();
            // Update all user permissions, and set according to Method
            if ($request->has('permissions'))
            {
                $method->permissions()->sync($request->permissions);
                foreach($method->users as $user)
                {
                    $methods = $user->roles;
                    $user->roles()->sync($method);
                    $user->permissions()->sync($request->permissions);
                    // dd($methods);
                }
            }
            DB::commit();
            Session::flash('success',"Role [$method->title] updated");
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        return redirect()->back();
    }

    public function destroy(Method $method)
    {
        $method->delete();
        return redirect()->route('method.index');
    }
}
