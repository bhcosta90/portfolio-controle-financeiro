<?php

namespace Core\Application\Payment\Shared\Enums;

use Core\Shared\Traits\EnumTrait;

enum PaymentTypeEnum: int
{
    use EnumTrait;

    case CREDIT = 1;
    case DEBIT = 2;
}
