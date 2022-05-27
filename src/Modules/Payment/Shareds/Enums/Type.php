<?php

namespace Costa\Modules\Payment\Shareds\Enums;

enum Type: int
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
