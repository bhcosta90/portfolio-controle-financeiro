<?php

namespace Core\Financial\Payment\Enums;

use Core\Shared\Traits\EnumTrait;

enum ChargeStatusEnum: int
{
    use EnumTrait;
    
    case PROCESSING = 1;
    case PROCESSED = 2;
}