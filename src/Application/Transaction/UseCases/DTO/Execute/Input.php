<?php

namespace Core\Application\Transaction\UseCases\DTO\Execute;

class Input
{
    public function __construct(
        public string $tenant,
        public string $id,
    ) {
        
    }
}
