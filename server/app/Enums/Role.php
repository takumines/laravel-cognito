<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

class Role extends Enum
{
    public const ADMIN = 'admin';

    public const PRE_MEMBER = 'pre_member';

    public const GENERAL_MEMBER = 'general_member';
}