<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class RegisterEmailRequest extends FormRequest
{
    protected $id;

    public function __construct() {
        $this->id = Auth::check() ? Auth::user()->id : null;
    }
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
        return [
            'email' => 'required|email:rfc,dns|max: 50|unique:users,email,'.$this->id.',_id,is_deleted,false'
        ];
    }
}
