<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

class Role extends Enum
{
    public const ADMIN = 'admin';

    public const WORKER = 'worker';

    public const RESIDENCE = 'residence';

    public const MEMBER = 'member';
}