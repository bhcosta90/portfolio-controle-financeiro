<?php

namespace Tests\Unit\Costa\Shareds\Contracts;

use Costa\Shareds\Contracts\TransactionContract;
use Mockery;
use stdClass;

trait MockTransactionContractTrait
{
    /**
     * @return \Mockery\MockInterface|TransactionContract
     */
    public function mockTransactionContract()
    {
        /** @var \Mockery\MockInterface */
        $mock = Mockery::mock(stdClass::class, TransactionContract::class);
        $mock->shouldReceive('rollback')->shouldReceive('commit');
        return $mock;
    }
}
