<?php

namespace Karacraft\RolesAndPermissions\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class MethodRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->isMethod('post'))
        {
            return [
                'title' => 'required|unique:methods|min:4'
            ];
        }
        else if ($this->isMethod('put')){
            $method = json_decode($this->request->get('method'));
            return [
                'title' => 'required|min:4|unique:methods,title,' . $method->id,
            ];
        }
    }
}
