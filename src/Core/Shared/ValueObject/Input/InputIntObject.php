<?php

namespace Costa\Shared\ValueObject\Input;

use Costa\Shared\Validations\DomainValidation;

class InputIntObject
{
    public function __construct(public ?int $value, $acceptNull = false)
    {
        if ($acceptNull) {
            DomainValidation::numericCanNullAndMin($value);
        } else {
            DomainValidation::numericMin($value);
        }
    }
}
