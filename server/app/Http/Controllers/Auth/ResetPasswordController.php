<?php

namespace App\Http\Controllers\Auth;

use App\Cognito\CognitoClient;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */
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
        $this->client = $client;
    }

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * パスワード変更をcognitoにリクエストする
     *
     * @param User $user
     * @param string $password
     */
    public function resetPassword(User $user, string $password)
    {
        $this->client->resetPassword($user->email, $password);
    }

    /**
     * Cognitoのパスワード設定に合わせて変更
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required', 'string', 'min:8', 'confirmed',
                'regex:/\A(?=.*?[a-z])(?=.*?[A-Z])(?=.*?\d)[a-zA-Z\d]{8,100}+\z/'
            ],
        ];
    }
}
