<?php

namespace Tests\Unit\Costa\Modules\Charge\Entities;

use Costa\Modules\Charge\Entities\RecurrenceEntity;
use Costa\Shareds\ValueObjects\Input\InputNameObject;
use Costa\Shareds\ValueObjects\UuidObject;
use DateTime;
use Mockery;

trait MockRecurrenceEntityTrait
{
    /**
     * @return \Mockery\MockInterface|RecurrenceEntity
     */
    public function mockRecurrenceEntity(
        string $name = 'test of customer',
        int $days = 1,
        ?UuidObject $id = null,
        ?DateTime $createdAt = null,
    ) {
        return Mockery::mock(stdClass::class, RecurrenceEntity::class, [
            new InputNameObject(value: $name),
            $days,
            $id,
            $createdAt,
        ]);
    }
}
