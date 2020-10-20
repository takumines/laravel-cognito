<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

class ApplicationStatus extends Enum
{
    public const  DRAUGHT = 'draught';

    public const APPLIED = 'applied';

    public const APPROVED = 'approved';

    public const REJECTED = 'rejected';
}
