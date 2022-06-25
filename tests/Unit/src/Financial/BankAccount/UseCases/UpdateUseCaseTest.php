<?php

namespace Tests\Unit\src\Financial\BankAccount\UseCases;

use Core\Financial\Account\Domain\AccountEntity;
use Core\Financial\Account\Repository\AccountRepositoryInterface;
use Core\Financial\BankAccount\Domain\BankAccountEntity as Entity;
use Core\Financial\BankAccount\Repository\BankAccountRepositoryInterface as Repo;
use Core\Financial\BankAccount\UseCases\UpdateUseCase;
use Core\Financial\BankAccount\UseCases\DTO\Update\{UpdateInput, UpdateOutput};
use Core\Shared\Interfaces\TransactionInterface;
use Core\Shared\ValueObjects\EntityObject;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class UpdateUseCaseTest extends TestCase
{
    public function testHandle()
    {
        $entity = Entity::create('bruno', 50, $id = Uuid::uuid4());
        $account = AccountEntity::create(new EntityObject($entity->id(), $entity));

        /** @var Repo|Mockery\MockInterface */
        $mock = Mockery::mock(stdClass::class, Repo::class);
        $mock->shouldReceive('find')->andReturn($entity);
        $mock->shouldReceive('update')->andReturn(true);

        /** @var AccountRepositoryInterface|Mockery\MockInterface */
        $mockAccount = Mockery::mock(stdClass::class, AccountRepositoryInterface::class);
        $mockAccount->shouldReceive('find')->andReturn($account);
        $mockAccount->shouldReceive('add');

        /** @var TransactionInterface|Mockery\MockInterface */
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mockTransaction->shouldReceive('commit');
        $mockTransaction->shouldReceive('rollback');
        
        /** @var UpdateInput|Mockery\MockInterface */
        $mockInput = $this->getMockClass(originalClassName: UpdateInput::class);

        $uc = new UpdateUseCase(
            repo: $mock,
            account: $mockAccount,
            transaction: $mockTransaction,
        );

        $handle = $uc->handle(new $mockInput($id, 'bruno costa', 60));
        $mock->shouldHaveReceived('find')->times(1);
        $mock->shouldHaveReceived('update')->times(1);

        $mockAccount->shouldHaveReceived('find')->times(1);
        $mockAccount->shouldHaveReceived('add')->times(1);
        $mockAccount->shouldNotHaveReceived('sub');
        $this->assertInstanceOf(UpdateOutput::class, $handle);
    }

    public function testHandleSub()
    {
        $entity = Entity::create('bruno', 50, $id = Uuid::uuid4());
        $account = AccountEntity::create(new EntityObject($entity->id(), $entity));

        /** @var Repo|Mockery\MockInterface */
        $mock = Mockery::mock(stdClass::class, Repo::class);
        $mock->shouldReceive('find')->andReturn($entity);
        $mock->shouldReceive('update')->andReturn(true);

        /** @var AccountRepositoryInterface|Mockery\MockInterface */
        $mockAccount = Mockery::mock(stdClass::class, AccountRepositoryInterface::class);
        $mockAccount->shouldReceive('find')->andReturn($account);
        $mockAccount->shouldReceive('sub');

        /** @var TransactionInterface|Mockery\MockInterface */
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mockTransaction->shouldReceive('commit');
        $mockTransaction->shouldReceive('rollback');
        
        /** @var UpdateInput|Mockery\MockInterface */
        $mockInput = $this->getMockClass(originalClassName: UpdateInput::class);

        $uc = new UpdateUseCase(
            repo: $mock,
            account: $mockAccount,
            transaction: $mockTransaction,
        );

        $handle = $uc->handle(new $mockInput($id, 'bruno costa', -60));
        $mock->shouldHaveReceived('find')->times(1);
        $mock->shouldHaveReceived('update')->times(1);

        $mockAccount->shouldHaveReceived('find')->times(1);
        $mockAccount->shouldHaveReceived('sub')->times(1);
        $mockAccount->shouldNotHaveReceived('add');
        $this->assertInstanceOf(UpdateOutput::class, $handle);
    }
}
