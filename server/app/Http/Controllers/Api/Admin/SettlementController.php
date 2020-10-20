<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\ApplicationStatus;
use App\Http\Controllers\Controller;
use App\Models\Application;
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
    public function capture(Request $request, Application $application)
    {
        $user = $request->user();
        $stripe = new StripeClient(config('services.stripe.secret'));
        $stripe->paymentIntents->capture($request->charge_id);
        $currentApplication = $application->where('user_id', $user->id)->firstOrFail();
        $currentApplication->status = ApplicationStatus::APPROVED;

        return response()->json([
            'message' => 'success'
        ]);
    }
}
