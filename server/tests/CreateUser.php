<?php


namespace Tests;


use App\Models\User;

trait CreateUser
{
    /**
     * 新規登録直後のuserを生成
     *
     * @param $scenario
     * @return User
     */
    public static function createUser($scenario): User
    {
        $user = factory(User::class, $scenario)->create();

        return $user;
    }
}