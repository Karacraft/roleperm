<?php

namespace Karacraft\RolesAndPermissions\Http\Requests;

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
        $rules = [
            'title' => 'required|unique:permissions|min:4',
            'model' => 'required|min:4',
            'method' => 'required|min:4'
        ];
        //  Use Alterantes
        if ($this->isMethod('post'))
        {
            return $this->createRules();
        }
        else if ($this->isMethod('put')){
            return $this->updateRules();
        }
    }

    public function createRules()
    {
        return [
            'title' => 'required|unique:permissions|min:4',
            'model' => 'required|min:4',
            'method' => 'required|min:4'
        ];
    }

    public function updateRules()
    {
        return [
            'title' => 'required|min:4|unique:permissions,title,'.$this->permission->id ,
            'model' => 'required|min:4',
            'method' => 'required|min:4'
        ];
    }
}
