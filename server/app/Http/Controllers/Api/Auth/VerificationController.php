<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    use VerifiesEmails;

    /**
     * VerificationController constructor.
     */
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
    public function verify(Request $request, User $user)
    {
        $currentUser = $user->findOrFail($request->route('id'));
        if (! hash_equals((string) $request->route('id'), (string) $currentUser->getKey())) {
            throw new AuthorizationException;
        }

        if (! hash_equals((string) $request->route('hash'), sha1($currentUser->getEmailForVerification()))) {
            throw new AuthorizationException;
        }

        return response()->json([
            'message' => 'Email Verified',
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resend(Request $request, User $user)
    {
        $currentUser = $user->where('email', $request->input('email'))->firstOrFail();
        if ($currentUser->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Already Verified User'
            ]);
        }
        $currentUser->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Send Verify Email'
        ]);
    }
}
