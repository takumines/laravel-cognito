<?php

namespace Tests\Feature\Member;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreateUser;
use Tests\LaravelPassportTestCase;

class ProfileUpdateTest extends LaravelPassportTestCase
{
    use RefreshDatabase;
    use CreateUser;

    /**
     * @param array $param
     * @dataProvider trueProfileData
     */
    public function test_基本情報登録_認証あり(array $param)
    {
        $user = $this->createUser('pre_member');

        $response = $this->json('POST', '/api/profiles', $param['requestData'], $this->generateToken($user));
        $response->assertStatus(200);
        $this->dbHasRequestedValueConfirmation($param['requestData'], $user);
    }

    /**
     * @param array $param
     * @dataProvider trueProfileData
     */
    public function test_基本情報登録_認証なし(array $param)
    {
        $response = $this->json('POST', '/api/profiles', $param['requestData'], $this->withOutToken());
        $response->assertStatus(401);
    }

    /**
     * @param array $param
     * @dataProvider errorProfileData
     */
    public function test_基本情報登録_異常値(array $param)
    {
        $user = $this->createUser('pre_member');
        $response = $this->json('POST', '/api/profiles', $param['requestData'], $this->generateToken($user));

        $response->assertStatus(422);
    }

    /**
     * DBに基本情報が登録されているかチェック
     *
     * @param $request
     * @param $user
     */
    private function dbHasRequestedValueConfirmation($request, $user)
    {
        $this->assertDatabaseHas('profiles', [
            'user_id'             => $user->id,
            'first_name'          => $request['first_name'],
            'last_name'           => $request['last_name'],
            'first_name_kana'     => $request['first_name_kana'],
            'last_name_kana'      => $request['last_name_kana'],
            'postal_code'         => $request['postal_code'],
            'prefecture'          => $request['prefecture'],
            'municipality_county' => $request['municipality_county'],
            'address'             => $request['address'],
            'birthday'            => $request['birthday'],
            'annual_income'       => $request['annual_income'],
            'entry_sheet'         => $request['entry_sheet']
        ]);
    }

    public function trueProfileData(): array
    {
        return [
            '正常値_基本情報登録' => [
                [
                    'requestData' => [
                        'first_name'          => str_repeat('あ', 50),
                        'last_name'           => str_repeat('あ', 50),
                        'first_name_kana'     => str_repeat('あ', 50),
                        'last_name_kana'      => str_repeat('あ', 50),
                        'postal_code'         => 8820854,
                        'prefecture'          => str_repeat('あ', 50),
                        'municipality_county' => str_repeat('あ', 255),
                        'address'             => str_repeat('あ', 255),
                        'birthday'            => '1970/01/01',
                        'annual_income'       => 300,
                        'entry_sheet'         => str_repeat('あ', 1000),
                        'identification_photo_front'
                                              => 'upload/identification/user/1/7d7302e272c78eeb9fd9935a05a3b353.jpg',
                        'identification_photo_reverse'
                                              => 'upload/identification/user/1/7d7302e272c78eeb9fd9935a05a3b353.jpg'
                    ]
                ]
            ]
        ];
    }

    public function errorProfileData(): array
    {
        return [
            '異常値_first_nameがstring型でない' => [
                [
                    'requestData' => [
                        'first_name'          => 11,
                        'last_name'           => str_repeat('あ', 50),
                        'first_name_kana'     => str_repeat('あ', 50),
                        'last_name_kana'      => str_repeat('あ', 50),
                        'postal_code'         => 8820854,
                        'prefecture'          => str_repeat('あ', 50),
                        'municipality_county' => str_repeat('あ', 255),
                        'address'             => str_repeat('あ', 255),
                        'birthday'            => '1970/01/01',
                        'annual_income'       => 300,
                        'entry_sheet'         => str_repeat('あ', 1000),
                    ]
                ]
            ],
            '異常値_last_nameがstring型ではない' => [
                [
                    'requestData' => [
                        'first_name'          => str_repeat('あ', 50),
                        'last_name'           => 11,
                        'first_name_kana'     => str_repeat('あ', 50),
                        'last_name_kana'      => str_repeat('あ', 50),
                        'postal_code'         => 8820854,
                        'prefecture'          => str_repeat('あ', 50),
                        'municipality_county' => str_repeat('あ', 255),
                        'address'             => str_repeat('あ', 255),
                        'birthday'            => '1970/01/01',
                        'annual_income'       => 300,
                        'entry_sheet'         => str_repeat('あ', 1000),
                    ]
                ]
            ],
            '異常値_first_name_kanaがstring型ではない' => [
                [
                    'requestData' => [
                        'first_name'          => str_repeat('あ', 50),
                        'last_name'           => str_repeat('あ', 50),
                        'first_name_kana'     => 11,
                        'last_name_kana'      => str_repeat('あ', 50),
                        'postal_code'         => 8820854,
                        'prefecture'          => str_repeat('あ', 50),
                        'municipality_county' => str_repeat('あ', 255),
                        'address'             => str_repeat('あ', 255),
                        'birthday'            => '1970/01/01',
                        'annual_income'       => 300,
                        'entry_sheet'         => str_repeat('あ', 1000),
                    ]
                ]
            ],
            '異常値_last_name_kanaがstring型ではない' => [
                [
                    'requestData' => [
                        'first_name'          => str_repeat('あ', 50),
                        'last_name'           => str_repeat('あ', 50),
                        'first_name_kana'     => str_repeat('あ', 50),
                        'last_name_kana'      => 11,
                        'postal_code'         => 8820854,
                        'prefecture'          => str_repeat('あ', 50),
                        'municipality_county' => str_repeat('あ', 255),
                        'address'             => str_repeat('あ', 255),
                        'birthday'            => '1970/01/01',
                        'annual_income'       => 300,
                        'entry_sheet'         => str_repeat('あ', 1000),
                    ]
                ]
            ],
            '異常値_postal_codeがint型ではない' => [
                [
                    'requestData' => [
                        'first_name'          => str_repeat('あ', 50),
                        'last_name'           => str_repeat('あ', 50),
                        'first_name_kana'     => str_repeat('あ', 50),
                        'last_name_kana'      => str_repeat('あ', 50),
                        'postal_code'         => str_repeat('あ', 7),
                        'prefecture'          => str_repeat('あ', 50),
                        'municipality_county' => str_repeat('あ', 255),
                        'address'             => str_repeat('あ', 255),
                        'birthday'            => '1970/01/01',
                        'annual_income'       => 300,
                        'entry_sheet'         => str_repeat('あ', 1000),
                    ]
                ]
            ],
            '異常値_prefectureがstring型ではない' => [
                [
                    'requestData' => [
                        'first_name'          => str_repeat('あ', 50),
                        'last_name'           => str_repeat('あ', 50),
                        'first_name_kana'     => str_repeat('あ', 50),
                        'last_name_kana'      => str_repeat('あ', 50),
                        'postal_code'         => 8820854,
                        'prefecture'          => 11,
                        'municipality_county' => str_repeat('あ', 255),
                        'address'             => str_repeat('あ', 255),
                        'birthday'            => '1970/01/01',
                        'annual_income'       => 300,
                        'entry_sheet'         => str_repeat('あ', 1000),
                    ]
                ]
            ],
            '異常値_municipality_countyがstring型ではない' => [
                [
                    'requestData' => [
                        'first_name'          => str_repeat('あ', 50),
                        'last_name'           => str_repeat('あ', 50),
                        'first_name_kana'     => str_repeat('あ', 50),
                        'last_name_kana'      => str_repeat('あ', 50),
                        'postal_code'         => 8820854,
                        'prefecture'          => str_repeat('あ', 50),
                        'municipality_county' => 11,
                        'address'             => str_repeat('あ', 255),
                        'birthday'            => '1970/01/01',
                        'annual_income'       => 300,
                        'entry_sheet'         => str_repeat('あ', 1000),
                    ]
                ]
            ],
            '異常値_addressがstring型ではない' => [
                [
                    'requestData' => [
                        'first_name'          => str_repeat('あ', 50),
                        'last_name'           => str_repeat('あ', 50),
                        'first_name_kana'     => str_repeat('あ', 50),
                        'last_name_kana'      => str_repeat('あ', 50),
                        'postal_code'         => 8820854,
                        'prefecture'          => str_repeat('あ', 50),
                        'municipality_county' => str_repeat('あ', 255),
                        'address'             => 11,
                        'birthday'            => '1970/01/01',
                        'annual_income'       => 300,
                        'entry_sheet'         => str_repeat('あ', 1000),
                    ]
                ]
            ],
            '異常値_birthdayがdate型ではない' => [
                [
                    'requestData' => [
                        'first_name'          => str_repeat('あ', 50),
                        'last_name'           => str_repeat('あ', 50),
                        'first_name_kana'     => str_repeat('あ', 50),
                        'last_name_kana'      => str_repeat('あ', 50),
                        'postal_code'         => 8820854,
                        'prefecture'          => str_repeat('あ', 50),
                        'municipality_county' => str_repeat('あ', 255),
                        'address'             => str_repeat('あ', 255),
                        'birthday'            => str_repeat('あ', 5),
                        'annual_income'       => 300,
                        'entry_sheet'         => str_repeat('あ', 1000),
                    ]
                ]
            ],
            '異常値_annual_incomeがint型ではない' => [
                [
                    'requestData' => [
                        'first_name'          => str_repeat('あ', 50),
                        'last_name'           => str_repeat('あ', 50),
                        'first_name_kana'     => str_repeat('あ', 50),
                        'last_name_kana'      => str_repeat('あ', 50),
                        'postal_code'         => 8820854,
                        'prefecture'          => str_repeat('あ', 50),
                        'municipality_county' => str_repeat('あ', 255),
                        'address'             => str_repeat('あ', 255),
                        'birthday'            => '1970/01/01',
                        'annual_income'       => str_repeat('あ', 5),
                        'entry_sheet'         => str_repeat('あ', 1000),
                    ]
                ]
            ],
            '異常値_entry_sheetがstring型でなはい' => [
                [
                    'requestData' => [
                        'first_name'          => str_repeat('あ', 50),
                        'last_name'           => str_repeat('あ', 50),
                        'first_name_kana'     => str_repeat('あ', 50),
                        'last_name_kana'      => str_repeat('あ', 50),
                        'postal_code'         => 8820854,
                        'prefecture'          => str_repeat('あ', 50),
                        'municipality_county' => str_repeat('あ', 255),
                        'address'             => str_repeat('あ', 255),
                        'birthday'            => '1970/01/01',
                        'annual_income'       => str_repeat('あ', 5),
                        'entry_sheet'         => 11,
                    ]
                ]
            ]
        ];
    }

}
