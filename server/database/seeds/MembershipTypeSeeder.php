<?php

use App\Enums\MembershipType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MembershipTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        DB::table('membership_types')->insert([
            [
                'type'        => MembershipType::RESIDENCE_RESORT,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'type'        => MembershipType::RESIDENCE,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'type'        => MembershipType::COWORKING_FIX,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'type'        => MembershipType::COWORKING_FREE,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]
        ]);
    }
}
