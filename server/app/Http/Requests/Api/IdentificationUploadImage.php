<?php

namespace App\Http\Requests\Api;

class IdentificationUploadImage extends ApiRequest
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
            'identification_image' => ['required', 'file', 'mimes:jpeg,png,jpg']
        ];
    }
}
