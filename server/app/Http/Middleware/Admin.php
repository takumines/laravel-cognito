<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Support\Facades\Auth;

class Admin
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
        if (!Auth::check()) {
            return response()->json([
                'message' => 'no login'
            ]);
        }

        if (Auth::user()->role === Role::MEMBER) {
            return response()->json([
                'message' => 'Permission Denied'
            ]);
        }

        if (Auth::user()->role === Role::ADMIN) {
            return $next($request);
        }
    }
}
