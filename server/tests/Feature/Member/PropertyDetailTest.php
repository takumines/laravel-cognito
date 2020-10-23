<?php

namespace Tests\Feature\Member;

use App\Enums\MembershipType;
use App\Http\Resources\PropertyDetail;
use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use MembershipTypeSeeder;
use PropertyMembershipTypeSeeder;
use PropertySeeder;
use Tests\CreateUser;
use Tests\LaravelPassportTestCase;

class PropertyDetailTest extends LaravelPassportTestCase
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

    public function test_物件詳細の取得_認証あり()
    {
        $property = Property::first();
        $user = $this->createUser('pre_member');
        $response = $this->json('GET', '/api/properties/' . $property->id, [], $this->generateToken($user));

        $propertyDetail = $response->json()['data'];

        $this->checkProperty($propertyDetail);
        $this->checkMembershipProperty($propertyDetail['membership']);
        $this->checkMembershipType($propertyDetail['membership']);
        $response->assertResource(new PropertyDetail($property));
        $response->assertStatus(200);
    }

    public function test_物件詳細の取得_認証なし()
    {
        $property = Property::first();
        $response = $this->json('GET', '/api/properties' . $property->id, [], $this->withOutToken());

        $response->assertStatus(404);
    }

    /**
     * 物件詳細のプロパティキーをチェック
     *
     * @param array $propertyDetail
     */
    private function checkProperty(array $propertyDetail)
    {
        $keys = ['id', 'name', 'description', 'image', 'membership'];
        foreach ($keys as $key) {
            $this->assertTrue(
                array_key_exists($key, $propertyDetail),
                $key . 'が物件詳細情報のキーに含まれていません'
            );
        }
    }

    /**
     * 会員種別のプロパティをチェック
     *
     * @param array $memberships
     */
    private function checkMembershipProperty(array $memberships)
    {
        $keys = ['id', 'membershipType', 'price'];
        foreach ($memberships as $membership) {
            foreach ($keys as $key) {
                $this->assertTrue(
                    array_key_exists($key, $membership),
                    $key . 'が会員種別情報のキーに含まれていません'
                );
            }
        }

        // foreachを使わずに処理を書く練習
//        array_map(
//            function ($membership) use ($keys) {
//                return $result = array_map(
//                    function ($key) use ($membership) {
//                        return array_key_exists($key, $membership);
//                    },
//                    $keys
//                );
//            },
//            $memberships
//        );
    }

    /**
     * 会員種別のプロパティをチェックテスト
     *
     * @param array $memberships
     */
    public function example3(array $memberships)
    {
        $keys = ['id', 'membershipType', 'price'];
        foreach ($memberships as $membership) {
            $collection = collect($membership);
            $result = $collection->except($keys);
            $this->assertTrue(is_array($result),
                '$keysに含まれていないキーが入っています'
            );
        }
    }

    /**
     * 練習コード
     * 会員種別のプロパティをチェック
     *
     * @param array $memberships
     */
    public function example1(array $memberships)
    {
        $keys = ['id', 'membershipType', 'price'];
        $result = array_map(function($membership) {
                 return $this->example2($membership['membershipType']);
                            }, $memberships);
        foreach ($result as $value) {
            $this->assertTrue(
                $value,
                'が会員種別情報のキーに含まれていません'
            );
        }
    }

    /**
     * 練習コード
     *
     * @param string $membershipType
     * @return bool
     */
    public function example2(string $membershipType)
    {
        return MembershipType::isValid($membershipType);
    }

    /**
     * 会員種別の文字列がEnumに含まれているかチェック
     *
     * @param array $memberships
     */
    private function checkMembershipType(array $memberships)
    {
        foreach ($memberships as $membership) {
            $this->assertTrue(MembershipType::isValid($membership['membershipType']),
                              'MembershipTypeEnumに指定のない文字列が含まれています');
        }
    }

}
