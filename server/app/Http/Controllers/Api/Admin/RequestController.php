<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Request;
use Illuminate\Support\Facades\Storage;

class RequestController extends Controller
{
    public function download(Request $request, Profile $profile)
    {
        $applyProfile = $profile->where('user_id', $request->user_id)->first();
        $identificationImage = $applyProfile->identification_photo_front;
        $result = Storage::disk('s3')->getAdapter()->getclient()->getObject([
            'Bucket' => 'test-niclass',
            'Key'    => $identificationImage
        ]);
        header('Content-Type: ' . $result['ContentType']);
        header("Content-Disposition: attachment");

        print($result['Body']);

        return response()->json([
            'message' => 'success'
        ]);
    }
}
