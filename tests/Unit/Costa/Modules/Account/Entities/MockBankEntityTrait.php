<?php

namespace Tests\Unit\Costa\Modules\Account\Entities;

use Costa\Modules\Account\Entities\BankEntity;
use Costa\Modules\Account\ValueObjects\BankObject;
use Costa\Shareds\ValueObjects\Input\InputNameObject;
use Costa\Shareds\ValueObjects\UuidObject;
use DateTime;
use Mockery;

trait MockBankEntityTrait
{
    /**
     * @return \Mockery\MockInterface|BankEntity
     */
    public function mockBankEntity(
        string $name = 'bank in test',
        ?BankObject $bank = null,
        ?UuidObject $id = null,
        ?DateTime $createdAt = null,
    ) {
        return Mockery::mock(stdClass::class, BankEntity::class, [
            new InputNameObject(value: $name),
            $bank,
            true,
            $id,
            $createdAt,
        ]);
    }
}
