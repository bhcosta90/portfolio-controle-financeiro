<?php

namespace Costa\Modules\Payment\Shared\Enums;

enum PaymentType: int {
    case CREDIT = 1;
    case DEBIT = 2;
}