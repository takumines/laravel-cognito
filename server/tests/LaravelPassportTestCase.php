<?php


namespace Tests;


use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\ClientRepository;

class LaravelPassportTestCase extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Laravel Passportのトークンを生成
     *
     * @param $user
     * @return string[]
     */
    public static function generateToken($user)
    {
        // Personal Access ClientをDBに作成
        $clientRepository = new ClientRepository();
        $client = $clientRepository->createPersonalAccessClient(
            $user->id,
            'Test Personal Access Client',
            url('/')
        );
        DB::table('oauth_personal_access_clients')->insert(
            [
                'client_id' => $client->id,
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime(),
            ]
        );

        // ユーザーと、それに紐づく認証TokenをDBに作成

        $token = $user->createToken('user_token')->accessToken;

        // リクエストのヘッダーを設定
        return [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
    }

    /**
     * トークン無しのレスポンスヘッダーを返す
     *
     * @return string[]
     */
    public static function withOutToken()
    {
        return [
            'Accept' => 'application/json'
        ];
    }
}