<?php

namespace Core\Application\Payment\Shared\Enums;

use Core\Shared\Traits\EnumTrait;

enum PaymentStatusEnum: int
{
    use EnumTrait;

    case PENDING = 1;
    case PROCESSING = 2;
    case PROCESSED = 3;
}
