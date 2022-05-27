<?php

namespace Costa\Shareds\Abstracts;

use Costa\Shareds\Contracts\EntityInterface;
use Costa\Shareds\ValueObjects\UuidObject;
use DateTime;
use DeepCopy\Exception\PropertyException;

abstract class EntityAbstract implements EntityInterface
{
    public function __construct()
    {
        if (property_exists($this, 'createdAt')) {
            $this->createdAt = $this->createdAt ?: new DateTime();
        }

        if (property_exists($this, 'updatedAt')) {
            $this->updatedAt = $this->updatedAt ?: new DateTime();
        }

        if (property_exists($this, 'id')) {
            $this->id = $this->id ?: UuidObject::random();
        }
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->{$property};
        }

        $className = get_class($this);
        throw new PropertyException("Property {$property} not found in class {$className}");
    }

    public function id(): string
    {
        return (string) $this->id;
    }

    public function createdAt(): string
    {
        return $this->createdAt->format('Y-m-d H:i:s');
    }
}
