<?php

namespace Tests\Unit\src\Financial\BankAccount\UseCases;

use Core\Financial\Account\Domain\AccountEntity;
use Core\Financial\Account\Repository\AccountRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Core\Financial\BankAccount\Domain\BankAccountEntity as Entity;
use Core\Financial\BankAccount\Repository\BankAccountRepositoryInterface as Repo;
use Core\Financial\BankAccount\UseCases\DeleteUseCase;
use Core\Shared\Interfaces\TransactionInterface;
use Core\Shared\UseCases\Delete\{DeleteInput, DeleteOutput};
use Core\Shared\ValueObjects\EntityObject;
use Mockery;
use Ramsey\Uuid\Uuid;

class DeleteUseCaseTest extends TestCase
{
    public function testHandleError()
    {
        $entity = Entity::create('bruno', 50, $id = Uuid::uuid4());
        $account = AccountEntity::create(new EntityObject($entity->id(), $entity));
        
        /** @var Repo|Mockery\MockInterface */
        $mock = Mockery::mock(stdClass::class, Repo::class);
        $mock->shouldReceive('find')->andReturn($entity);
        $mock->shouldReceive('delete')->andReturn(false);

        /** @var AccountRepositoryInterface|Mockery\MockInterface */
        $mockAccount = Mockery::mock(stdClass::class, AccountRepositoryInterface::class);
        $mockAccount->shouldReceive('find')->andReturn($account);
        $mockAccount->shouldReceive('delete')->andReturn(false);

        /** @var TransactionInterface|Mockery\MockInterface */
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mockTransaction->shouldReceive('commit');
        $mockTransaction->shouldReceive('rollback');
        
        /** @var UpdateInput|Mockery\MockInterface */
        $mockInput = $this->getMockClass(originalClassName: DeleteInput::class);

        $uc = new DeleteUseCase(
            repo: $mock,
            account: $mockAccount,
            transaction: $mockTransaction,
        );

        $handle = $uc->handle(new $mockInput($id));
        $mock->shouldHaveReceived('find')->times(1);
        $mock->shouldHaveReceived('delete')->times(1);

        $mockAccount->shouldHaveReceived('find')->times(1);
        $mockAccount->shouldHaveReceived('delete')->times(1);
        $this->assertInstanceOf(DeleteOutput::class, $handle);
        $this->assertFalse($handle->success);
    }

    public function testHandleErrorAccount()
    {
        $entity = Entity::create('bruno', 50, $id = Uuid::uuid4());
        $account = AccountEntity::create(new EntityObject($entity->id(), $entity));
        
        /** @var Repo|Mockery\MockInterface */
        $mock = Mockery::mock(stdClass::class, Repo::class);
        $mock->shouldReceive('find')->andReturn($entity);
        $mock->shouldReceive('delete')->andReturn(true);

        /** @var AccountRepositoryInterface|Mockery\MockInterface */
        $mockAccount = Mockery::mock(stdClass::class, AccountRepositoryInterface::class);
        $mockAccount->shouldReceive('find')->andReturn($account);
        $mockAccount->shouldReceive('delete')->andReturn(false);

        /** @var TransactionInterface|Mockery\MockInterface */
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mockTransaction->shouldReceive('commit');
        $mockTransaction->shouldReceive('rollback');
        
        /** @var UpdateInput|Mockery\MockInterface */
        $mockInput = $this->getMockClass(originalClassName: DeleteInput::class);

        $uc = new DeleteUseCase(
            repo: $mock,
            account: $mockAccount,
            transaction: $mockTransaction,
        );

        $handle = $uc->handle(new $mockInput($id));
        $mock->shouldHaveReceived('find')->times(1);
        $mock->shouldHaveReceived('delete')->times(1);

        $mockAccount->shouldHaveReceived('find')->times(1);
        $mockAccount->shouldHaveReceived('delete')->times(1);
        $this->assertInstanceOf(DeleteOutput::class, $handle);
        $this->assertFalse($handle->success);
    }

    public function testHandleErrorBankAccount()
    {
        $entity = Entity::create('bruno', 50, $id = Uuid::uuid4());
        $account = AccountEntity::create(new EntityObject($entity->id(), $entity));
        
        /** @var Repo|Mockery\MockInterface */
        $mock = Mockery::mock(stdClass::class, Repo::class);
        $mock->shouldReceive('find')->andReturn($entity);
        $mock->shouldReceive('delete')->andReturn(false);

        /** @var AccountRepositoryInterface|Mockery\MockInterface */
        $mockAccount = Mockery::mock(stdClass::class, AccountRepositoryInterface::class);
        $mockAccount->shouldReceive('find')->andReturn($account);
        $mockAccount->shouldReceive('delete')->andReturn(true);

        /** @var TransactionInterface|Mockery\MockInterface */
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mockTransaction->shouldReceive('commit');
        $mockTransaction->shouldReceive('rollback');
        
        /** @var UpdateInput|Mockery\MockInterface */
        $mockInput = $this->getMockClass(originalClassName: DeleteInput::class);

        $uc = new DeleteUseCase(
            repo: $mock,
            account: $mockAccount,
            transaction: $mockTransaction,
        );

        $handle = $uc->handle(new $mockInput($id));
        $mock->shouldHaveReceived('find')->times(1);
        $mock->shouldHaveReceived('delete')->times(1);

        $mockAccount->shouldHaveReceived('find')->times(1);
        $mockAccount->shouldHaveReceived('delete')->times(1);

        $this->assertInstanceOf(DeleteOutput::class, $handle);
        $this->assertFalse($handle->success);
    }

    public function testHandle()
    {
        $entity = Entity::create('bruno', 50, $id = Uuid::uuid4());
        $account = AccountEntity::create(new EntityObject($entity->id(), $entity));
        
        /** @var Repo|Mockery\MockInterface */
        $mock = Mockery::mock(stdClass::class, Repo::class);
        $mock->shouldReceive('find')->andReturn($entity);
        $mock->shouldReceive('delete')->andReturn(true);

        /** @var AccountRepositoryInterface|Mockery\MockInterface */
        $mockAccount = Mockery::mock(stdClass::class, AccountRepositoryInterface::class);
        $mockAccount->shouldReceive('find')->andReturn($account);
        $mockAccount->shouldReceive('delete')->andReturn(true);

        /** @var TransactionInterface|Mockery\MockInterface */
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mockTransaction->shouldReceive('commit');
        $mockTransaction->shouldReceive('rollback');
        
        /** @var UpdateInput|Mockery\MockInterface */
        $mockInput = $this->getMockClass(originalClassName: DeleteInput::class);

        $uc = new DeleteUseCase(
            repo: $mock,
            account: $mockAccount,
            transaction: $mockTransaction,
        );

        $handle = $uc->handle(new $mockInput($id));
        $mock->shouldHaveReceived('find')->times(1);
        $mock->shouldHaveReceived('delete')->times(1);

        $mockAccount->shouldHaveReceived('find')->times(1);
        $mockAccount->shouldHaveReceived('delete')->times(1);
        $this->assertInstanceOf(DeleteOutput::class, $handle);
        $this->assertTrue($handle->success);
    }
}
