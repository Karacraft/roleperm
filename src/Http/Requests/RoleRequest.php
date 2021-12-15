<?php

namespace Karacraft\Roleperm\Http\Requests;

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
        return [
            'title' => 'required|unique:roles|min:5',
            'description' => 'required|min:10',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Atlease 5 Characters - Title should be unique',
            'description.required' => 'Minimum 10 Characters Required for Description', 
        ];
    }
}
