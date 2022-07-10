<?php

namespace Karacraft\RolesAndPermissions\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
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
        //  Use Alterantes
        if ($this->isMethod('post'))
        {
            return [
                // 'title' => 'required|unique:permissions|min:4',
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