<?php

namespace App\Http\Requests\Api;


class ProfileRequest extends ApiRequest
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
            'first_name'                   => ['required', 'string', 'max:50'],
            'last_name'                    => ['required', 'string', 'max:50'],
            'first_name_kana'              => ['required', 'string', 'max:50'],
            'last_name_kana'               => ['required', 'string', 'max:50'],
            'postal_code'                  => ['required', 'integer', 'digits:7'],
            'prefecture'                   => ['required', 'string', 'max:50'],
            'municipality_county'          => ['required', 'string', 'max:255'],
            'address'                      => ['required', 'string', 'max:255'],
            'building_name'                => ['string', 'max:255'],
            'birthday'                     => ['required', 'date', 'before:today'],
            'annual_income'                => ['required', 'integer'],
            'entry_sheet'                  => ['required', 'string', 'max:1000'],
            'identification_photo_front'   => ['file', 'mimes:jpeg,png,jpg'],
            'identification_photo_reverse' => ['file', 'mimes:jpeg,png,jpg'],
        ];
    }
}
