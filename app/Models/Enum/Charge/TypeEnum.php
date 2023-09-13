<?php

namespace App\Models\Enum\Charge;

use Exception;

enum TypeEnum: int
{
    case UNIQUE = 1;
    case PARCEL = 2;
    case MONTHLY = 3;

    /**
     * @throws Exception
     */
    public function getName(): string
    {
        return match ($this) {
            self::UNIQUE => __('Não recorrente'),
            self::PARCEL => __('Parcelar ou repetir'),
            self::MONTHLY => __('Fixa mensal'),
            default => throw new Exception()
        };
    }
}
