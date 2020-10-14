<?php

use App\Models\Facility;
use App\Models\MembershipType;
use Illuminate\Database\Seeder;
use App\Enums\MembershipType as Type;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FacilityMembershipTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(MembershipType $membershipType, Facility $facility)
    {
        $membershipTypes = $membershipType->all();

        $facilities = $facility->all();

        foreach ($facilities as $facility) {
            foreach ($membershipTypes as $membershipType) {
                $facility->membershipTypes()->attach(
                    $membershipType->id,
                    [
                        'price' => $this->insertPrice($membershipType->type),
                    ]
                );
            }
        }
    }

    public function insertPrice(string $type)
    {
        switch ($type) {
            case Type::RESIDENCE_RESORT:
                return 50000;

            case Type::RESIDENCE:
                return 40000;

            case Type::COWORKING_FIX:
                return 15000;

            case Type::COWORKING_FREE:
                return 10000;
        }
    }
}
