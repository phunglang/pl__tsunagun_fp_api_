<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
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
            'email' => [
                'required',
                'email:rfc,dns',
                'max: 50',
                Rule::unique('users')->where(fn($query) =>
                        $query
                            ->where('email', $this->email)
                            ->where(fn($q) =>
                                $q
                                    ->where('is_deleted', false)
                                    ->orWhereNull('is_deleted')
                            )
                            ->where('_id', '<>', $this->id)
                        // 'unique:users,email,'.$this->id.',_id,is_deleted,false'
                )
            ]
        ];
    }
}
