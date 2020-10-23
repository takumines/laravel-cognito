<?php

namespace Tests\Feature\Member;

use Illuminate\Foundation\Testing\RefreshDatabase;
use MembershipTypeSeeder;
use PropertyMembershipTypeSeeder;
use PropertySeeder;
use Tests\CreateUser;
use Tests\LaravelPassportTestCase;

class ApplicationStoreTest extends LaravelPassportTestCase
{
    use RefreshDatabase;
    use CreateUser;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(PropertySeeder::class);
        $this->seed(MembershipTypeSeeder::class);
        $this->seed(PropertyMembershipTypeSeeder::class);

    }

    /**
     * @param array $param
     * @dataProvider trueApplicationData
     */
    public function test_物件・会員種別選択_認証あり(array $param)
    {
        $user = $this->createUser('pre_member');
        $response = $this->json('POST', '/api/application', $param['requestData'], $this->generateToken($user));


    }

    public function trueApplicationData():array
    {
        return [
            // テストデータのケースを書く
        ];
    }
}
