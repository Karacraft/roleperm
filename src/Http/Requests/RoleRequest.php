<?php

namespace Karacraft\RolesAndPermissions\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // return false;
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'title' => 'required|unique:roles|min:4',
            'description' => 'required|min:10',
        ];
        //  Use Alterantes
        if ($this->isMethod('post'))
        {
            return $this->createRules();
        }
        else if ($this->isMethod('put'))
        {
            // If we are updating only permissions
            if($this->has('updatePermissions'))
            {
                return $this->noRules();
            }else 
            {
                //  If we are updating role
                return $this->updateRules();
            }
        }
    }

    public function createRules()
    {
        return [
            'title' => 'required|unique:roles|min:4',
            'description' => 'required|min:10',
        ];
    }

    public function updateRules()
    {
        //'email' => 'required|email|unique:users|regex:/^[A-Za-z0-9\.]*@(auvitronics)[.](com)$/',
        // https://stackoverflow.com/a/56851217/4853427
        $role = json_decode($this->request->get('role'));
        // dd($this->request->all());
        // unique:table,column,except,idColumn
        return [
<<<<<<< HEAD
            'title' => 'required|min:4|unique:roles,title,'.$this->id ,
=======
            'title' => 'required|unique:permissions|min:4|unique:roles,title,' .$role->id,
>>>>>>> d36e5af51167234458e2209070b4f2e4ebfe9ad2
            'description' => 'required|min:10',
        ];
    }

    public function noRules()
    {
        return [];
    }
}
