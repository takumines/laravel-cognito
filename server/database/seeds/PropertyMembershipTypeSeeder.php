<?php

use App\Models\Property;
use App\Models\MembershipType;
use Illuminate\Database\Seeder;
use App\Enums\MembershipType as Type;

class PropertyMembershipTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(MembershipType $membershipType, Property $property)
    {
        $membershipTypes = $membershipType->all();

        $properties = $property->all();

        foreach ($properties as $property) {
            foreach ($membershipTypes as $membershipType) {
                $property->membershipTypes()->attach(
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
