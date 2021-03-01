<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
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
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'idUserEndPoint.required' => 'A idUserEndPoint is required',
            'messageText.required_without' => 'A messageText or imageFile is required',
            'imageFile.required_without' => 'A imageFile or messageText  is required',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'messageText' => 'required_without:imageFile',
            'imageFile' => 'required_without:messageText',
            'idUserEndPoint' => 'required',
        ];
    }
}
