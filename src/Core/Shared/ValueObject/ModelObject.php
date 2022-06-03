<?php

namespace Costa\Shared\ValueObject;

class ModelObject
{
    public function __construct(
        public int|string $id,
        public string|object $type,
    ) {
        if (gettype($this->type) === 'object') {
            $this->type = get_class($this->type);
        }
    }
}
