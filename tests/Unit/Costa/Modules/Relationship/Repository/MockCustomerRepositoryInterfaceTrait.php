<?php

namespace Tests\Unit\Costa\Modules\Relationship\Repository;

use Costa\Modules\Relationship\Repository\CustomerRepositoryInterface;
use Mockery;

trait MockCustomerRepositoryInterfaceTrait
{
    /**
     * @return Mockery\MockInterface|CustomerRepositoryInterface
     */
    public function mockCustomerRepositoryInterface()
    {
        return Mockery::mock(stdClass::class, CustomerRepositoryInterface::class);
    }
}
