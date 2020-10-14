<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use App\Http\Resources\FacilityList;
use App\Models\Facility;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Collection;

class FacilityController extends Controller
{
    /**
     * 物件一覧
     *
     * @param Facility $facility
     * @return FacilityList|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Facility $facility)
    {
        $facilities = $facility->all();

        return FacilityList::collection($facilities);
    }

    /**
     * 物件詳細
     *
     * @param Facility $facility
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Facility $facility)
    {
        return response()->json([
           'facility' => $facility
        ]);
    }
}
