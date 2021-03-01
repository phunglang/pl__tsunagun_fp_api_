<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $arrRules =  [
            'email' => 'required|email:rfc,dns|max: 50|unique:users',
            'username' => 'required|max:50',
            'birthday' => 'required|date',
            'connect_areas' => 'required|array',
            'department' => 'required|min:0|max:3'
        ];
        if (request()->provider) $arrRules['client_id'] = 'required';
        else $arrRules['password'] = 'required';
        return $arrRules;
    }
}
