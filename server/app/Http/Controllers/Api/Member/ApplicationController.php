<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SelectRequest;
use App\Http\Resources\Member\User;

class RequestController extends Controller
{
    /**
     * 物件・会員種別選択
     *
     * @param SelectRequest $request
     * @return User
     */
    public function store(SelectRequest $request)
    {
        $user = $request->user();
        $user->request()->create($request->all());

        return new User($user);
    }
}
