<?php

namespace Costa\Shareds\Exceptions;

use Exception;

class EntityValidationException extends Exception
{
    private array $errors;

    public function __construct(string $message, array $errors)
    {
        parent::__construct($message, 422, null);
        $this->errors = $errors;
    }

    public function toArray(): array
    {
        return $this->errors;
    }
}
