<?php

namespace App\Models\Enum\Charge;

use Exception;

enum ParcelEnum: int
{
    case TOTAL = 1;
    case MONTH = 2;

    /**
     * @throws Exception
     */
    public function getName(): string
    {
        return match ($this) {
            self::TOTAL => __('Valor da parcela'),
            self::MONTH => __('Parcela fixa'),
            default => throw new Exception()
        };
    }
}
