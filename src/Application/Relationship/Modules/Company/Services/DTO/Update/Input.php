<?php

namespace Core\Application\Relationship\Modules\Company\Services\DTO\Update;

class Input
{
    public function __construct(
        public string $id,
        public string $name,
    )
    {
        //
    }
}
