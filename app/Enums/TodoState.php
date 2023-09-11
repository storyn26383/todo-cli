<?php

namespace App\Enums;

enum TodoState: string
{
    case Pending = 'pending';
    case Done = 'done';
}
