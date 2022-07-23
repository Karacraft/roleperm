<?php

namespace Karacraft\RolesAndPermissions\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'title' => 'required|unique:roles|min:4',
            'description' => 'required|min:10',
        ];
        //  Use Alterantes
        if ($this->isMethod('post'))
        {
            return [
                'title' => 'required|unique:roles|min:4',
                'description' => 'required|min:10',
            ];
        }
        else if ($this->isMethod('put'))
        {
            // If we are updating only permissions
            if($this->has('updatePermissions'))
            {
                return [];
            }else 
            {
                //  If we are updating role
                //'email' => 'required|email|unique:users|regex:/^[A-Za-z0-9\.]*@(auvitronics)[.](com)$/',
                // https://stackoverflow.com/a/56851217/4853427
                $role = json_decode($this->request->get('role'));
                return [
                    'title' => 'required|unique:permissions|min:4|unique:roles,title,' .$role->id,
                    'description' => 'required|min:10',
                ];
            }
        }
    }
}
