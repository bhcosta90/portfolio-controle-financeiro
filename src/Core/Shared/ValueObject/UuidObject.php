<?php

namespace Costa\Shared\ValueObject;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid as RamseyUuid;

class UuidObject
{
    public function __construct(
        protected string $value
    ) {
        $this->ensureIsValid($value);
    }

    private function ensureIsValid(string $id)
    {
        if (!RamseyUuid::isValid($id)) {
            throw new InvalidArgumentException(sprintf("<%s> does not allow the value <%s>", static::class, $id));
        }
    }

    public static function random(): self
    {
        return new self(RamseyUuid::uuid4());
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
