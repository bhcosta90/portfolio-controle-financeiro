<?php

namespace Costa\Modules\Charge\UseCases\Charge\DTO\Find;

use Costa\Shareds\ValueObjects\ModelObject;
use DateTime;
use Exception;

class Input
{
    public function __construct(
        public int|string $id,
    ) {
        //
    }
}
