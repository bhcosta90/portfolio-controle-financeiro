<?php

namespace Costa\Modules\Charge\Shareds\Enums;

enum Status: int
{
    case PENDING = 1;
    case PARTIAL = 2;
    case COMPLETED = 3;

    public static function toArray(): array
    {
        return array_map(
            fn (self $type) => $type->value,
            self::cases()
        );
    }
}
