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
        $rules = [
            'title' => 'required|unique:roles|min:4',
            'description' => 'required|min:10',
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
            'title' => 'required|unique:roles|min:5',
            'description' => 'required|min:10',
        ];
    }

    public function updateRules()
    {
        return [
            'title' => 'required|min:5|unique:roles,title,' . $this->id ,
            'description' => 'required|min:10',
        ];
    }
}
