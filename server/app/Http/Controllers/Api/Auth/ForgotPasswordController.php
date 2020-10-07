<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $validate = $this->validateEmail($request->all());

        if ($validate->fails()) {
            return response()->json([
                'message' => 'Email is Invalid',
            ]);
        }

        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        return $response === Password::RESET_LINK_SENT
            ? $this->sendResetLinkResponse()
            : $this->sendResetLinkFailedResponse($response);
    }

    /**
     * メールチェック
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validateEmail(array $data)
    {
        return Validator::make($data, [
            'email' => 'required|email',
        ]);
    }

    /**
     * @return JsonResponse
     */
    protected function sendResetLinkResponse(): JsonResponse
    {
        return response()->json([
            'message' => 'Send Rest Mail',
        ]);
    }

    /**
     * @param $response
     * @return JsonResponse
     */
    protected function sendResetLinkFailedResponse($response): JsonResponse
    {
        return response()->json([
            'message' => trans($response),
        ]);
    }
}
