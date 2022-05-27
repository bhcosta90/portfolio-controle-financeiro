<?php

namespace Tests\Unit\Costa\Modules\Charge\Entities;

use Costa\Modules\Charge\Entities\ChargeReceiveEntity;
use Costa\Shareds\ValueObjects\Input\InputNameObject;
use Costa\Shareds\ValueObjects\ModelObject;
use Costa\Shareds\ValueObjects\UuidObject;
use DateTime;
use Mockery;

trait MockChargeReceiveEntityTrait
{
    /**
     * @return \Mockery\MockInterface|ChargeReceiveEntity
     */
    public function mockChargeReceiveEntity(
        InputNameObject $title = new InputNameObject('teste charge'),
        ?InputNameObject $description = new InputNameObject('teste description'),
        ModelObject $model = new ModelObject(1, 'teste'),
        float $value = 50,
        DateTime $date = new DateTime(),
        ?DateTime $dateStart = null,
        ?DateTime $dateFinish = null,
        ?UuidObject $recurrence = null,
        ?UuidObject $id = null,
        ?DateTime $createdAt = null,
    ) {
        return Mockery::mock(stdClass::class, ChargeReceiveEntity::class, [
            $title,
            $description,
            $model,
            $value,
            $date,
            $dateStart,
            $dateFinish,
            $recurrence,
            $id,
            $createdAt,
        ]);
    }
}
