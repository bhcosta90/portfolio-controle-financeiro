<?php

namespace Core\Application\Transaction\Shared\Enums;

use Core\Shared\Traits\EnumTrait;

enum TransactionTypeEnum: int
{
    use EnumTrait;

    case CREDIT = 1;
    case DEBIT = 2;
}
