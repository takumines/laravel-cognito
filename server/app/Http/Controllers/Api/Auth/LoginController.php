<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthManager;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * @var AuthManager
     */
    private $authManager;

    /**
     * LoginController constructor.
     */
    public function __construct(AuthManager $authManager)
    {
        $this->middleware('guest')->except('logout');
        $this->authManager = $authManager;
    }

    /**
     * ログイン処理、ログイン失敗時の処理を行う
     *
     * @param Request $request
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response|void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request, User $user)
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        // 初回ログイン時の処理
        $currentUser = $user->where('email', $request->input('email'))->firstOrFail();
        if (!$currentUser->email_verified_at) {
            $this->validateFirstLogin($request);
            if (! hash_equals((string) $request->input('token'), sha1($currentUser->getEmailForVerification()))) {
                throw new AuthorizationException;
            }
            $currentUser->markEmailAsVerified();
            $this->authManager->confirmSignUp($currentUser->email);
        }

        try {
            if ($this->attemptLogin($request)) {
                $user = $request->user();
                $token = $user->createToken('user_token')->accessToken;

                return response()->json([
                    'token' => $token,
                ]);
            }
        } catch (CognitoIdentityProviderException $ce) {
            return $this->sendFailedCognitoResponse($ce);
        } catch (\Exception $e) {
            return $this->sendFailedLoginResponse($request);
        }

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * cognitoの認証に失敗した際の処理
     *
     * @param CognitoIdentityProviderException $exception
     * @throws ValidationException
     */
    private function sendFailedCognitoResponse(CognitoIdentityProviderException $exception)
    {
        throw ValidationException::withMessages([
            $this->username() => $exception->getAwsErrorMessage()
        ]);
    }

    /**
     * 入力エラーの回数が上限を超えた場合の処理
     *
     * @param Request $request
     * @throws ValidationException
     */
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        throw ValidationException::withMessages([
            $this->username() => [
                Lang::get('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds /60),
                ])
            ],
        ])->status(Response::HTTP_TOO_MANY_REQUESTS);
    }

    /**
     * 初回ログイン時のバリデーション
     *
     * @param Request $request
     */
    protected function validateFirstLogin(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);
    }
}
