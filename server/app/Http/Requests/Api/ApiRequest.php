<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class ApiRequest extends FormRequest
{
    /**
     * バリデーションのレスポンスをJsonで返す
     *
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        $data = [
            'errors' => $validator->errors()->toArray(),
        ];

        throw new HttpResponseException(response()->json(
            $data,
            422,
            [],
            JSON_UNESCAPED_UNICODE
        ));
    }
}
