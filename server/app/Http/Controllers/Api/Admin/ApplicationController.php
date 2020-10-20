<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Application;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function download(Application $application, Profile $profile)
    {
        $applyProfile = $profile->where('user_id', $application->user_id)->first();
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
