<?php

namespace Tests\Unit\Costa\Modules\Relationship\Entities;

use Costa\Modules\Relationship\Entities\SupplierEntity;
use Costa\Shareds\ValueObjects\Input\InputNameObject;
use Costa\Shareds\ValueObjects\UuidObject;
use DateTime;
use Mockery;
use stdClass;

trait MockSupplierEntityTrait
{
    /**
     * @return \Mockery\MockInterface|SupplierEntity
     */
    public function mockSupplierEntity(
        string $name = 'test of supplier',
        ?UuidObject $id = null,
        ?DateTime $createdAt = null,
    ) {
        return Mockery::mock(stdClass::class, SupplierEntity::class, [
            new InputNameObject($name),
            $id,
            $createdAt,
        ]);
    }
}
