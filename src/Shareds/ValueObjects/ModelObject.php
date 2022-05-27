<?php

namespace Costa\Shareds\ValueObjects;

class ModelObject
{
    public function __construct(
        public int|string $id,
        public string|object $type,
        public ?string $value = null,
    ) {
        if (gettype($this->type) === 'object') {
            $this->type = get_class($this->type);
        }
    }
}
