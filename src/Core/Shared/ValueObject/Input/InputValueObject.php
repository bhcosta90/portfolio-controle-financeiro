<?php

namespace Costa\Shared\ValueObject\Input;

use Costa\Shared\Validations\DomainValidation;

class InputValueObject
{
    public function __construct(public ?float $value, $acceptNull = false)
    {
        if ($acceptNull) {
            DomainValidation::floatCanNullAndMin($value);
        } else {
            DomainValidation::floatMin($value);
        }
    }
}
