<?php

namespace App\Http\Requests\Api;

class SelectRequest extends ApiRequest
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
            'property_id'        => ['required', 'integer', 'exists:App\Models\Property,id'],
            'membership_type_id' => ['required', 'integer', 'exists:App\Models\MembershipType,id'],
        ];
    }
}
