<?php

namespace Tests\Unit\src\Financial\BankAccount\UseCases;

use Core\Financial\Account\Repository\AccountRepositoryInterface;
use Core\Financial\BankAccount\Repository\BankAccountRepositoryInterface as Repo;
use Core\Financial\BankAccount\UseCases\CreateUseCase;
use Core\Financial\BankAccount\UseCases\DTO\Create\{CreateInput, CreateOutput};
use Core\Shared\Interfaces\TransactionInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

class CreateUseCaseTest extends TestCase
{
    public function testHandle()
    {
        /** @var Repo|Mockery\MockInterface */
        $mock = Mockery::mock(stdClass::class, Repo::class);
        $mock->shouldReceive('insert')->andReturn(true);

        /** @var AccountRepositoryInterface|Mockery\MockInterface */
        $mockAccount = Mockery::mock(stdClass::class, AccountRepositoryInterface::class);
        $mockAccount->shouldReceive('insert')->andReturn(true);

        /** @var TransactionInterface|Mockery\MockInterface */
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mockTransaction->shouldReceive('commit');
        $mockTransaction->shouldReceive('rollback');

        /** @var CreateInput|Mockery\MockInterface */
        $mockInput = $this->getMockClass(originalClassName: CreateInput::class);

        $uc = new CreateUseCase(
            repo: $mock,
            account: $mockAccount,
            transaction: $mockTransaction,
        );

        $handle = $uc->handle(new $mockInput('bruno costa', 50));
        $mock->shouldHaveReceived('insert')->times(1);
        $mockAccount->shouldHaveReceived('insert')->times(1);
        $this->assertInstanceOf(CreateOutput::class, $handle);
    }
}
