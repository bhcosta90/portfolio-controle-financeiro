<?php

namespace Core\Application\Relationship\Modules\Company\Services\DTO\Update;

class Output
{
    public function __construct(
        public string $name,
        public string $id,
    ) {
        //
    }
}
