<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProfileRequest;
use App\Http\Resources\Member\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function update(ProfileRequest $request, Profile $profile)
    {
        $user = $request->user();
        if ($user->profile === null) {
            $user->profile()->create($request->all());
        } else {
            $user->profile()->update($request->all());
        }
//        $profile->insert($request);

        return new User($user);
    }
}
