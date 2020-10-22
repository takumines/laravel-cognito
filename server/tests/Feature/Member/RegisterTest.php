<?php

namespace Tests\Feature\Member;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreateUser;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    use CreateUser;

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @param array $param
     * @dataProvider trueRegisterData
     */
    public function test_正常な値_新規登録(array $param)
    {
        $response = $this->json('POST', '/api/register', $param['requestData']);
        $response->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'email' => $param['requestData']['email'],
        ]);
    }

    /**
     * @param array $param
     * @dataProvider errorRegisterData
     */
    public function test_異常な値_新規登録(array $param)
    {
        $this->createUser('pre_member');
        $response = $this->json('POST', '/api/register', $param['requestData']);
        $response->assertStatus(422);

    }

    public function trueRegisterData(): array
    {
        return [
            '新規登録_正常値' => [
                [
                    'requestData' => [
                        'email'                 => 'test@example.com',
                        'password'              => 'Test1234',
                        'password_confirmation' => 'Test1234',
                    ]
                ]
            ]
        ];
    }

    public function errorRegisterData(): array
    {
        return [
            '異常値_メールアドレス不備' => [
                [
                    'requestData' => [
                        'email'                 => 'testexample.com',
                        'password'              => 'Test1234',
                        'password_confirmation' => 'Test1234'
                    ]
                ]
            ],
            '異常値_パスワード文字数が短い' => [
                [
                    'requestData' => [
                        'email'                 => 'test1@example.com',
                        'password'              => 'Test123',
                        'password_confirmation' => 'Test123',
                    ]
                ]
            ],
            '異常値_パスワード確認との不一致' => [
                [
                    'requestData' => [
                        'email'                 => 'test1@example.com',
                        'password'              => 'Test1234',
                        'password_confirmation' => 'Test4321',
                    ]
                ]
            ],
            '異常値_既に登録されているメールアドレス' => [
                [
                    'requestData' => [
                        'email'                 => 'test@example.com',
                        'password'              => 'Test1234',
                        'password_confirmation' => 'Test1234',
                    ]
                ]
            ]
        ];
    }
}
