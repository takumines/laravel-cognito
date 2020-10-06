<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    use VerifiesEmails;

    public function __construct()
    {
        $this->middleware('throttle:6.1');
    }

    /**
     * メール認証を行う
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthorizationException
     */
    public function verify(Request $request)
    {
        if (! hash_equals((string) $request->route('id'), (string) $request->user()->getKey())) {
            throw new AuthorizationException;
        }

        if (! hash_equals((string) $request->route('hash'), sha1($request->user()->getEmailForVerification()))) {
            throw new AuthorizationException;
        }

        $user = $request->user();
        if(!$user->email_verified_at) {
            $user->markEmailAsVerified();
            event(new Verified($user));

            return response()->json([
                'message' => 'Email Verified',
            ]);
        }

        return response()->json([
            'message' => 'Email Verify Failed'
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resend(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'message' => 'No Such User'
            ]);
        }

        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Already Verified User'
            ]);
        }

        $user->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Send Verify Email'
        ]);
    }
}
