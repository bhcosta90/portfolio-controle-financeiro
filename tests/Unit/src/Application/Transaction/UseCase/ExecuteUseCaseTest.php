<?php

namespace Tests\Unit\src\Application\Transaction\UseCase;

use Core\Application\Transaction\Domain\TransactionEntity;
use Core\Application\Transaction\UseCases\ExecuteUseCase;
use Core\Application\Transaction\UseCases\DTO\Execute\{Input, Output};
use Mockery;
use Tests\UnitCase as TestCase;

class ExecuteUseCaseTest extends TestCase
{
    public function testCredit()
    {
        $uc = new ExecuteUseCase(
            $mockTransactionRepository = $this->mockTransactionRepository(),
            $this->mockTransaction(),
            $mockAccountRepository = $this->mockAccountRepository(),
        );

        $id = $this->id();
        $ret = $this->mock(fn () => $uc->handle($this->mockInput($id)), [
            [
                'mock' => $mockTransactionRepository,
                'action' => 'find',
                'return' => TransactionEntity::create(
                    $tenant = $this->id(),
                    $this->id(),
                    'test',
                    $tenant,
                    $this->id(),
                    $this->id(),
                    "test",
                    null,
                    null,
                    null,
                    50,
                    1,
                    date('Y-m-d'),
                    1,
                    $id,
                ),
                'times' => 1,
            ],
            [
                'mock' => $mockAccountRepository,
                'action' => 'addValue',
                'return' => true,
                'times' => 1,
            ],
            [
                'mock' => $mockTransactionRepository,
                'action' => 'update',
                'return' => true,
                'times' => 1,
            ],
        ]);
        $this->assertInstanceOf(Output::class, $ret);
    }

    public function testDebit()
    {
        $uc = new ExecuteUseCase(
            $mockTransactionRepository = $this->mockTransactionRepository(),
            $this->mockTransaction(),
            $mockAccountRepository = $this->mockAccountRepository(),
        );

        $id = $this->id();
        $ret = $this->mock(fn () => $uc->handle($this->mockInput($id)), [
            [
                'mock' => $mockTransactionRepository,
                'action' => 'find',
                'return' => TransactionEntity::create(
                    $tenant = $this->id(),
                    $this->id(),
                    'test',
                    $tenant,
                    $this->id(),
                    $this->id(),
                    "test",
                    null,
                    null,
                    null,
                    50,
                    2,
                    date('Y-m-d'),
                    1,
                    $id,
                ),
                'times' => 1,
            ],
            [
                'mock' => $mockAccountRepository,
                'action' => 'subValue',
                'return' => true,
                'times' => 1,
            ],
            [
                'mock' => $mockTransactionRepository,
                'action' => 'update',
                'return' => true,
                'times' => 1,
            ],
        ]);
        
        $this->assertInstanceOf(Output::class, $ret);
    }

    /** @return Input|Mockery\MockInterface */
    protected function mockInput(
        ?string $tenant = null,
        ?string $id = null,
    ) {
        return Mockery::mock(Input::class, [
            $tenant ?: $this->id(),
            $id ?: $this->id(),
        ]);
    }
}
