<?php

namespace Tests\Unit\Costa\Modules\Relationship\Entities;

use Costa\Modules\Relationship\Entities\CustomerEntity;
use Costa\Shareds\ValueObjects\Input\InputNameObject;
use Costa\Shareds\ValueObjects\UuidObject;
use DateTime;
use Mockery;
use stdClass;

trait MockCustomerEntityTrait
{
    /**
     * @return \Mockery\MockInterface|CustomerEntity
     */
    public function mockCustomerEntity(
        string $name = 'test of customer',
        ?UuidObject $id = null,
        ?DateTime $createdAt = null,
    ) {
        return Mockery::mock(stdClass::class, CustomerEntity::class, [
            new InputNameObject($name),
            $id,
            $createdAt,
        ]);
    }
}
