<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        DB::table('properties')->insert([
            [
                'name'        => '海部屋',
                'description' => '海が見える部屋です',
                'image'       => 'https://test-niclass.s3-ap-northeast-1.amazonaws.com/house/house.jpg',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => '山部屋',
                'description' => '山が見える部屋です',
                'image'       => 'https://test-niclass.s3-ap-northeast-1.amazonaws.com/house/house.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'        => '川部屋',
                'description' => '川が見える部屋です',
                'image'       => 'https://test-niclass.s3-ap-northeast-1.amazonaws.com/house/house.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'        => '街中コワーキング',
                'description' => '街が見えるコワーキングです',
                'image'       => 'https://test-niclass.s3-ap-northeast-1.amazonaws.com/house/house.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);
    }
}
