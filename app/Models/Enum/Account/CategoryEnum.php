<?php

namespace App\Models\Enum\Account;

enum CategoryEnum: int
{
    case MY_WALLET = 1;
    case CURRENT_ACCOUNT = 2;
    case SAVINGS_ACCOUNT = 3;
}
