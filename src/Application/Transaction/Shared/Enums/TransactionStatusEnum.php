<?php

namespace Core\Application\Transaction\Shared\Enums;

use Core\Shared\Traits\EnumTrait;

enum TransactionStatusEnum: int
{
    use EnumTrait;

    case PENDING = 1;
    case COMPLETE = 2;
}
