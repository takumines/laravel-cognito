<?php

namespace App\Http\Middleware;

use App\Enums\MembershipType;
use App\Enums\Role;
use Closure;
use Illuminate\Support\Facades\Auth;

class Membership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $membership = Auth::user()->role;

        if ($membership === Role::PRE_MEMBER) {
            return response()->json([
                'message' => 'membership only'
            ]);
        }

        if (MembershipType::isValid($membership) || $membership === Role::ADMIN) {
            return $next($request);
        }
    }
}
