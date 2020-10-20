<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthoriRequest;
use App\Http\Resources\Member\User;
use App\Models\Request;

class SettlementController extends Controller
{
    /**
     * 仮払い処理を行う
     *
     * @param AuthoriRequest $request
     * @param Request $application
     * @return User
     */
    public function authorization(AuthoriRequest $request, Request $application)
    {
        $user = $request->user();
        if (!$user->hasPaymentMethod()) {
            $user->updateDefaultPaymentMethod($request->paymentMethod);
        }
        $charge = $user->charge($request->amount, $request->payment_method, [
            'currency' => 'jpy',
            'capture_method' => 'manual'
        ]);
        $currentApplication = $application->where('user_id', $user->id)->firstOrFail();
        $currentApplication->charge_id = $charge->id;
        $currentApplication->save();
        
        return new User($user);
    }
}
