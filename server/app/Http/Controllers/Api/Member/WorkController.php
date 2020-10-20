<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use App\Http\Resources\Member\User;
use App\Models\Work;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    /**
     * WorkController constructor.
     */
    public function __construct()
    {
        $this->middleware('can:update,work')->only('update');
    }

    /**
     * 仕事一覧
     *
     * @param Work $work
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Work $work)
    {
        $works = $work->all();

        return response()->json([
            'works' => $works
        ]);
    }

    /**
     * 仕事の詳細
     *
     * @param Work $work
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Work $work)
    {
        return response()->json([
            'work' => $work
        ]);
    }

    /**
     * 仕事の新規作成
     *
     * @param Request $request
     * @return User
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $user->works()->createMany([$request->all()]);

        return new User($user);
    }

    /**
     * 仕事の更新
     *
     * @param Request $request
     * @param Work $work
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Work $work)
    {
        $work->fill($request->all())->save();

        return response()->json([
            'work' => $work
        ]);
    }

    /**
     * 仕事の削除
     *
     * @param Work $work
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Work $work)
    {
        $work->delete();

        return response()->json([
            'message' => 'success'
        ]);
    }
}
