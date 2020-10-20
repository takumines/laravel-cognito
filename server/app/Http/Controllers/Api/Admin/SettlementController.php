<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\StripeClient;

class SettlementController extends Controller
{
    /**
     * 未キャプチャを決済する
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function capture(Request $request)
    {
        $stripe = new StripeClient(config('services.stripe.secret'));
        $stripe->paymentIntents->capture($request->charge_id);

        return response()->json([
            'message' => 'success'
        ]);
    }
}
