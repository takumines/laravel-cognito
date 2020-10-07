<?php

namespace App\Http\Controllers\Api\Auth;

use App\Cognito\CognitoClient;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * @var CognitoClient
     */
    protected $client;

    /**
     * ResetPasswordController constructor.
     * @param CognitoClient $client
     */
    public function __construct(CognitoClient $client)
    {
        $this->middleware('guest');
        $this->client = $client;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
        $validate = $this->validator($request->all());

        if ($validate->fails()) {
            return response()->json([
                'message' => $validate->errors()
            ]);
        }
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        return $response === Password::PASSWORD_RESET
            ? $this->sendResetResponse()
            : $this->sendResetFailedResponse($response);
    }

    /**
     * @param User $user
     * @param string $password
     */
    protected function resetPassword(User $user, string $password)
    {
        $this->client->resetPassword($user->email, $password);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendResetResponse()
    {
        return response()->json([
            'message' => 'Password Reset'
        ]);
    }

    /**
     * @param Response $response
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendResetFailedResponse(Response $response)
    {
        return response()->json([
            'message' => $response
        ]);
    }

    /**
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => [
                'required', 'string', 'min:8', 'confirmed',
                'regex:/\A(?=.*?[a-z])(?=.*?[A-Z])(?=.*?\d)[a-zA-Z\d]{8,100}+\z/'
            ],
        ]);
    }
}
