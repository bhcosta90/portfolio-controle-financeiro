<?php

namespace Tests\Unit\Costa\Modules\Account\Entities;

use Costa\Modules\Account\Entities\AccountEntity;
use Costa\Shareds\ValueObjects\ModelObject;
use Costa\Shareds\ValueObjects\UuidObject;
use DateTime;
use Mockery;
use stdClass;

trait MockAccountEntityTrait
{
    /**
     * @return \Mockery\MockInterface|AccountEntity
     */
    public function mockAccountEntity(
        float $value = 0,
        ModelObject $model = new ModelObject(1, 'teste'),
        ?UuidObject $id = null,
        ?DateTime $createdAt = null,
    ) {
        return Mockery::mock(stdClass::class, AccountEntity::class, [
            $model,
            $value,
            $id,
            $createdAt,
        ]);
    }
}
