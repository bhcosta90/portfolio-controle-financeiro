<?php

namespace Core\Application\Relationship\Modules\Company\Services\DTO\Find;

class Output
{
    public function __construct(
        public string $name,
        public string $id,
    ) {
        //
    }
}
