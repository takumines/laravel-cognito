<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

class MembershipType extends Enum
{
    public const RESIDENCE_RESORT = 'residence_resort';

    public const RESIDENCE = 'residence';

    public const COWORKING_FIX = 'coworking_fixed';

    public const COWORKING_FREE = 'coworking_free';
}
