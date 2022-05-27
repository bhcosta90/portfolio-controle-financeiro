<?php

namespace Costa\Shareds\ValueObjects\Input;

use Costa\Shareds\Validations\DomainValidation;

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
