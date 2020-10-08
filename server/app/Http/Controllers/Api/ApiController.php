<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Member\User;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'success'
        ]);
    }

    public function show(Request $request)
    {
        $user = $request->user();

        return new User($user);
    }
}
