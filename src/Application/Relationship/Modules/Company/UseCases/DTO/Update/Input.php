<?php

namespace Core\Application\Relationship\Modules\Company\UseCases\DTO\Update;

class Input
{
    public function __construct(
        public string $id,
        public string $name,
    ) {
        //
    }
}
