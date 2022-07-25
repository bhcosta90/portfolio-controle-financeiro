<?php

namespace Core\Application\Relationship\Modules\Customer\UseCases\DTO\Update;

class Output
{
    public function __construct(
        public string $name,
        public string $id,
    ) {
        //
    }
}
