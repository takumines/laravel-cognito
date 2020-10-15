<?php

namespace App\Http\Resources;

use App\Models\MembershipType;
use Illuminate\Http\Resources\Json\JsonResource;


class MembershipTypeList extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'             => $this->id,
            'membershipType' => $this->membershipType(),
            'price'          => $this->whenPivotLoaded('membership_type_property', function () {
                return $this->pivot->price;
            }),
        ];
    }

    /**
     * 会員種別名を取得する
     *
     * @return mixed
     */
    private function membershipType()
    {
        $id = $this->whenPivotLoaded('membership_type_property', function () {
            return $this->pivot->membership_type_id;
        });

        $type = MembershipType::find($id)->type;

        return $type;
    }
}
