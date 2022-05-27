<?php

namespace Tests\Unit\Costa\Modules\Charge\Repository;

use Costa\Modules\Charge\Repository\RecurrenceRepositoryInterface;
use Mockery;

trait MockRecurrenceRepositoryInterfaceTrait
{
    /**
     * @return Mockery\MockInterface|RecurrenceRepositoryInterface
     */
    public function mockRecurrenceRepositoryInterface()
    {
        return Mockery::mock(stdClass::class, RecurrenceRepositoryInterface::class);
    }
}
