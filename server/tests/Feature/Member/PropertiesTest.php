<?php

namespace Tests\Feature\Member;

use App\Http\Resources\PropertyList;
use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PropertySeeder;
use Tests\CreateUser;
use Tests\LaravelPassportTestCase;

class PropertiesTest extends LaravelPassportTestCase
{
    use RefreshDatabase;
    use CreateUser;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(PropertySeeder::class);
    }

    public function test_物件一覧の取得_認証あり()
    {
        $properties = Property::all();
        $user = $this->createUser('pre_member');
        $response = $this->json('GET', '/api/properties', [], $this->generateToken($user));
        $propertyList = $response->json()['data'];

        $response->assertResource(PropertyList::collection($properties));
        $this->checkProperty($propertyList);
        $response->assertStatus(200);
    }

    public function test_物件一覧の取得_認証なし()
    {
        $response = $this->json('GET', '/api/properties', [], $this->withOutToken());

        $response->assertStatus(401);
    }

    /**
     * PropertyListのkeyが仕様通りかチェック
     *
     * @param array $propertyList
     */
    private function checkProperty(array $propertyList)
    {
        foreach ($propertyList as $property) {
            $keys = ['id', 'name', 'image'];
            foreach ($keys as $key) {
                $this->assertTrue(
                    array_key_exists($key, $property),
                    $key . 'が物件一覧情報のキーに含まれていません'
                );
            }
        }
    }
}
