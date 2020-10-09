<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

class Role extends Enum
{
    public const ADMIN = 'admin';

    public const WORKER_MEMBER = 'worker_member';

    public const RESIDENCE_MEMBER = 'residence_member';

    public const GENERAL_MEMBER = 'general_member';
}