<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IdentificationUploadImage;
use App\Http\Requests\Api\ProfileRequest;
use App\Http\Resources\Member\User;
use App\Models\Profile;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    /**
     * 基本情報・エントリーシート登録・更新
     *
     * @param ProfileRequest $request
     * @param Profile $profile
     * @return User
     */
    public function update(ProfileRequest $request)
    {
        $user = $request->user();
        if ($user->profile === null) {
            $user->profile()->create($request->all());
        } else {
            $user->profile()->update($request->all());
        }

        return new User($user);
    }

    /**
     * 本人確認書類のアップロード
     *
     * @param IdentificationUploadImage $request
     * @param Filesystem $filesystem
     * @return \Illuminate\Http\JsonResponse
     */
    public function identificationUpload(IdentificationUploadImage $request, Filesystem $filesystem)
    {
        $user = $request->user();
        $userId = $user->id;

        $extension = $request['identification_image']->getClientOriginalExtension();
        $filename = $filesystem->hash($request['identification_image']) . '.' . $extension;
        // 画像の向きを補正
        $resize_img = Image::make($request['identification_image'])->orientate()->save();
        $path = 'upload/identification/user/' . $userId . '/' . $filename;
        Storage::put($path, (string)$resize_img);

        return response()->json($path);
    }
}
