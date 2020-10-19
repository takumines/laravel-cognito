<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettlementController extends Controller
{
    public function authorization(Request $request)
    {
        $paymentMethod = $request->payment_method;
        $user = $request->user();
        $user->addPaymentMethod($paymentMethod);
        $user->charge($request->amount, $request->payment_method, [
            'currency' => 'jpy',

        ]);

        return response()->json([
            'message' => 'success'
        ]);
    }
}
