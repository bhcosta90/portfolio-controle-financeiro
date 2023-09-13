<?php

namespace App\Models\Enum\Account;

use Exception;

enum CategoryEnum: int
{
    case MY_WALLET = 1;
    case CURRENT_ACCOUNT = 2;
    case SAVINGS_ACCOUNT = 3;

    /**
     * @throws Exception
     */
    public function getName(): string
    {
        return match ($this) {
            self::MY_WALLET => __('Carteira'),
            self::CURRENT_ACCOUNT => __('Frete'),
            self::SAVINGS_ACCOUNT => __('Poupança'),
            default => throw new Exception()
        };
    }
}
