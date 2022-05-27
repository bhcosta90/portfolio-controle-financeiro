<?php

namespace Tests\Unit\Costa\Modules\Account\Repository;

use Costa\Modules\Account\Repository\BankRepositoryInterface;
use Mockery;

trait MockBankRepositoryInterfaceTrait
{
    /**
     * @return Mockery\MockInterface|BankRepositoryInterface
     */
    public function mockBankRepositoryInterface()
    {
        return Mockery::mock(stdClass::class, BankRepositoryInterface::class);
    }
}
