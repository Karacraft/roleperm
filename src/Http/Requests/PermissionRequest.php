<?php

namespace Karacraft\RolesAndPermissions\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        //  Use Alterantes
        if ($this->isMethod('post'))
        {
            return [
                'model' => 'required|min:4',
                'method' => 'required|array|min:1'
            ];
        }
        else if ($this->isMethod('put')){
            $permission = json_decode($this->request->get('permission'));
            return [
               
            ];
        }
    }
}