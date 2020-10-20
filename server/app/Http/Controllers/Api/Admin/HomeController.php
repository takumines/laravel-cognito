<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'admin'
        ]);
    }
}
