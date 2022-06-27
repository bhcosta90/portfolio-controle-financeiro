<?php

namespace Core\Financial\Charge\Shared\Enums;

use Core\Shared\Traits\EnumTrait;

enum ChargeTypeEnum: int
{
    use EnumTrait;
    
    case CREDIT = 1;
    case DEBIT = 2;
    case TRANSFER = 3;
}