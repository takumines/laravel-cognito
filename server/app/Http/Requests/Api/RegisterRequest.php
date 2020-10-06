<?php

namespace App\Http\Requests\Api;


class RegisterRequest extends ApiRequest
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
        return [
            'name'     => ['required', 'string'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users', 'cognito_user_unique'],
            'password' => [
                'required', 'string', 'min:8', 'confirmed',
                'regex:/\A(?=.*?[a-z])(?=.*?[A-Z])(?=.*?\d)[a-zA-Z\d]{8,100}+\z/'
            ],
        ];
    }
}
