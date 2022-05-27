<?php

namespace Tests\Unit\Costa\Modules\Charge\Repository;

use Costa\Modules\Charge\Repository\ChargeReceiveRepositoryInterface;
use Mockery;

trait MockChargeReceiveRepositoryInterfaceTrait
{
    /**
     * @return Mockery\MockInterface|ChargeReceiveRepositoryInterface
     */
    public function mockChargeReceiveRepositoryInterface()
    {
        /** @var Mockery\MockInterface|ChargeReceiveRepositoryInterface */
        $mock = Mockery::mock(stdClass::class, ChargeReceiveRepositoryInterface::class);
        $mock->shouldReceive('getValueTotal', 0);
        return $mock;
    }
}
