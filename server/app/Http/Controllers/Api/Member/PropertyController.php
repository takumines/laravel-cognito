<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use App\Http\Resources\PropertyDetail;
use App\Http\Resources\PropertyList;
use App\Models\Property;

class PropertyController extends Controller
{
    /**
     * 物件一覧
     *
     * @param Property $property
     * @return PropertyList|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Property $property)
    {
        $properties = $property->all();

        return PropertyList::collection($properties);
    }

    /**
     * 物件詳細
     *
     * @param Property $property
     * @return PropertyDetail
     */
    public function show(Property $property)
    {
        $property->membershipTypes;

        return new PropertyDetail($property);
    }
}
