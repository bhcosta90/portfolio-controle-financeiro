<?php

namespace Tests\Unit\Costa\Modules\Account\Repository;

use Costa\Modules\Account\Repository\AccountRepositoryInterface;
use Mockery;

trait MockAccountRepositoryInterfaceTrait
{
    /**
     * @return Mockery\MockInterface|AccountRepositoryInterface
     */
    public function mockAccountRepositoryInterface()
    {
        return Mockery::mock(stdClass::class, AccountRepositoryInterface::class);
    }
}
