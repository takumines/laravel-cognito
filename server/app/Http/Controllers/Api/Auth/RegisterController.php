<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;


class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * @var AuthManager
     */
    private $authManager;

    /**
     * RegisterController constructor.
     * @param AuthManager $authManager
     */
    public function __construct(AuthManager $authManager)
    {
        $this->middleware('guest');
        $this->authManager = $authManager;
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->all();
        $username = $this->authManager->register(
            $data['email'],
            $data['password'],
            [
                'email' => $data['email'],
            ]
        );

        $user = $this->create($data, $username);
        if ($user) {
            event(new Registered($user));
            $this->authManager->confirmSignUp($user->email);
        }

        $token = $user->createToken('user_token')->accessToken;

        return response()->json([
            'token' => $token,
        ]);
    }

    /**
     * @param array $data
     * @param string $username
     * @return mixed
     */
    protected function create(array $data, string $username)
    {
        return User::create([
            'email'            => $data['email'],
            'cognito_username' => $username,
        ]);
    }
}
