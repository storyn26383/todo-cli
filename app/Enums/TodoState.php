<?php

namespace App\Enums;

class TodoState
{
    const PENDING = 1 << 0;

    const DONE = 1 << 1;

    const ARCHIVED = 1 << 2;

    const ALL = self::PENDING | self::DONE | self::ARCHIVED;
}
