<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\ApplicationStatus;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\MembershipType;
use App\Models\User;
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
    public function capture(Request $request, Application $application, MembershipType $membershipType, User $user)
    {
        $selectUser = $user->find($request->user_id);
        $stripe = new StripeClient(config('services.stripe.secret'));
        $stripe->paymentIntents->capture($request->charge_id);
        $currentApplication = $application->where('user_id', $selectUser->id)->firstOrFail();
        $currentApplication->status = ApplicationStatus::APPROVED;
        $membership = $membershipType->where('id' ,$currentApplication->membership_type_id)->first();
        $selectUser->role = $membership->type;
        $currentApplication->save();
        $selectUser->save();

        return response()->json([
            'message' => 'success'
        ]);
    }
}
