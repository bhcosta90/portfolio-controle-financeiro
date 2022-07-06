<?php

namespace Core\Application\Charge\Shared\Enums;

use Core\Shared\Traits\EnumTrait;

enum ChargeStatusEnum: int
{
    use EnumTrait;
    
    case PENDING = 1;
    case PARTIAL = 2;
    case COMPLETED = 3;
}