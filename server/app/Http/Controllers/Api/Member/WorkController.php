<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use App\Http\Resources\Member\User;
use App\Models\Work;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:update,work')->only('update');
    }

    public function index(Work $work)
    {
        $works = $work->all();

        return response()->json([
            'works' => $works
        ]);
    }

    public function show(Work $work)
    {
        return response()->json([
            'work' => $work
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $user->works()->createMany([$request->all()]);

        return new User($user);
    }

    public function update(Request $request, Work $work)
    {
        $work->fill($request->all())->save();

        return response()->json([
            'work' => $work
        ]);
    }

    public function destroy(Work $work)
    {
        $work->delete();

        return response()->json([
            'message' => 'success'
        ]);
    }
}
