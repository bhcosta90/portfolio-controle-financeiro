<?php

namespace Costa\Modules\Charge\Utils\Enums;

enum ChargeTypeEnum: int
{
    case CREDIT = 1;
    case DEBIT = 2;

    public static function toArray(): array
    {
        return array_map(
            fn (self $type) => $type->value,
            self::cases()
        );
    }
}
