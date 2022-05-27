<?php

namespace Tests\Unit\Costa\Modules\Charge\Repository;

use Costa\Modules\Charge\Repository\ChargePaymentRepositoryInterface;
use Mockery;

trait MockChargePaymentRepositoryInterfaceTrait
{
    /**
     * @return Mockery\MockInterface|ChargePaymentRepositoryInterface
     */
    public function mockChargePaymentRepositoryInterface()
    {
        /** @var Mockery\MockInterface|ChargePaymentRepositoryInterface */
        $mock = Mockery::mock(stdClass::class, ChargePaymentRepositoryInterface::class);
        $mock->shouldReceive('getValueTotal', 0);
        return $mock;
    }
}
