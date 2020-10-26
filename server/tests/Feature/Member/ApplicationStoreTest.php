<?php

namespace Tests\Feature\Member;

use App\Enums\ApplicationStatus;
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
        $response = $this->json('POST', '/api/applications', $param['requestData'], $this->generateToken($user));

        $response->assertStatus(200);
        $this->dbHasRequestedValueConfirmation($param['requestData'], $user);
    }

    /**
     * @param array $param
     * @dataProvider errorApplicationData
     */
    public function test_物件・会員種別選択_異常値_認証あり(array $param)
    {
        $user = $this->createUser('pre_member');
        $response = $this->json('POST', '/api/applications', $param['requestData'], $this->generateToken($user));

        $response->assertStatus(422);
        $this->dbNotHasRequestedValueConfirmation($param['requestData'], $user);
    }

    /**
     * @param array $param
     * @dataProvider trueApplicationData
     */
    public function test_物件・会員種別選択_認証なし(array $param)
    {
        $response = $this->json('POST', '/api/applications', $param['requestData'], $this->withOutToken());

        $response->assertStatus(401);
    }

    /**
     * DBにリクエスト情報が登録されていることをチェック
     *
     * @param array $request
     * @param object $user
     */
    private function dbHasRequestedValueConfirmation(array $request, object $user)
    {
        $this->assertDatabaseHas('applications', [
            'user_id'            => $user->id,
            'property_id'        => $request['property_id'],
            'membership_type_id' => $request['membership_type_id'],
            'status'             => ApplicationStatus::DRAUGHT,
        ]);
    }

    /**
     * DBにリクエスト情報が登録されていないことをチェック
     *
     * @param array $request
     * @param object $user
     */
    private function dbNotHasRequestedValueConfirmation(array $request, object $user)
    {
        $this->assertDatabaseMissing('applications', [
            'user_id'            => $user->id,
            'property_id'        => $request['property_id'],
            'membership_type_id' => $request['membership_type_id'],
            'status'             => ApplicationStatus::DRAUGHT,
        ]);
    }

    public function trueApplicationData():array
    {
        return [
            '物件会員種別申請_正常値' => [
                [
                    'requestData' => [
                        'property_id'        => 1,
                        'membership_type_id' => 2
                    ]
                ]
            ]
        ];
    }

    public function errorApplicationData(): array
    {
        return [
            '異常値_物件IDが数値でない' => [
                [
                    'requestData' => [
                        'property_id'        => '１',
                        'membership_type_id' => 2
                    ]
                ]
            ],
            '異常値_会員種別IDが数値でない' => [
                [
                    'requestData' => [
                        'property_id'        => 1,
                        'membership_type_id' => '２'
                    ]
                ]
            ],
            '異常値_DBに存在していない物件ID' => [
                [
                    'requestData' => [
                        'property_id'        => 5,
                        'membership_type_id' => 2
                    ]
                ]
            ],
            '異常値_DBに存在していない会員種別ID' => [
                [
                    'requestData' => [
                        'property_id'        => 1,
                        'membership_type_id' => 5
                    ]
                ]
            ]
        ];
    }
}
