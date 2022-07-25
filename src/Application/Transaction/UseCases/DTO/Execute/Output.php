<?php

namespace Core\Application\Transaction\UseCases\DTO\Execute;

class Output
{
    public function __construct(
        public bool $success,
    ) {
        
    }
}
