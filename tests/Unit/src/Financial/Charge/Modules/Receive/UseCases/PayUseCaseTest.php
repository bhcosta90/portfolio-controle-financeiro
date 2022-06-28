<?php

namespace Tests\Unit\src\Financial\Charge\Modules\Receive\UseCases;

use Core\Financial\Account\Domain\AccountEntity;
use Core\Financial\Account\Repository\AccountRepositoryInterface;
use Core\Financial\BankAccount\Repository\BankAccountRepositoryInterface;
use Core\Financial\Charge\Modules\Receive\Domain\ReceiveEntity;
use Mockery;
use PHPUnit\Framework\TestCase;
use Core\Financial\Charge\Modules\Receive\UseCases\PayUseCase;
use Core\Financial\Charge\Modules\Receive\UseCases\DTO\Pay\{PayInput, PayOutput};
use Core\Financial\Charge\Modules\Receive\Repository\ReceiveRepositoryInterface as Repo;
use Core\Financial\Payment\Repository\PaymentRepositoryInterface;
use Core\Financial\Relationship\Modules\Customer\Domain\CustomerEntity;
use Core\Shared\Interfaces\PublishManagerInterface;
use Core\Shared\Interfaces\TransactionInterface;
use Core\Shared\ValueObjects\EntityObject;
use Ramsey\Uuid\Uuid;

class PayUseCaseTest extends TestCase
{
    public function testHandle()
    {
        $group = Uuid::uuid4();
        $id = Uuid::uuid4();

        $objEntity = ReceiveEntity::create(
            $group,
            50,
            CustomerEntity::create('bruno costa', null, null),
            1,
            '2022-01-01',
            null,
            0,
            null,
            $id,
        );

        /** @var Repo|Mockery\MockInterface */
        $mock = Mockery::mock(stdClass::class, Repo::class);
        $mock->shouldReceive('find')->andReturn($objEntity);
        $mock->shouldReceive('update')->andReturn(true);

        /** @var PaymentRepositoryInterface|Mockery\MockInterface */
        $mockPayment = Mockery::mock(stdClass::class, PaymentRepositoryInterface::class);
        $mockPayment->shouldReceive('insert')->andReturn(true);

        /** @var BankAccountRepositoryInterface|Mockery\MockInterface */
        $mockBankAccount = Mockery::mock(stdClass::class, BankAccountRepositoryInterface::class);

        /** @var AccountRepositoryInterface|Mockery\MockInterface */
        $mockAccount = Mockery::mock(stdClass::class, AccountRepositoryInterface::class);
        $mockAccount->shouldReceive('find')->andReturn(AccountEntity::create(new EntityObject($id, $objEntity), 0));

        /** @var TransactionInterface|Mockery\MockInterface */
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mockTransaction->shouldReceive('commit');
        $mockTransaction->shouldReceive('rollback');

        /** @var PublishManagerInterface|Mockery\MockInterface */
        $mockEvent = Mockery::mock(stdClass::class, PublishManagerInterface::class);
        $mockEvent->shouldReceive('dispatch');

        $uc = new PayUseCase(
            repo: $mock,
            payment: $mockPayment,
            bankAccount: $mockBankAccount,
            transaction: $mockTransaction,
            account: $mockAccount,
            event: $mockEvent
        );

        $ret = $uc->handle(new PayInput($id, 50, 25, date('Y-m-d')));
        $this->assertInstanceOf(PayOutput::class, $ret);
        $mock->shouldHaveReceived('update')->times(1);
        $mockPayment->shouldHaveReceived('insert')->times(1);
        $mockEvent->shouldHaveReceived('dispatch')->times(1);
        $this->assertTrue($ret->completed);
    }

    public function testHandleNotComplete()
    {
        $group = Uuid::uuid4();
        $id = Uuid::uuid4();

        $objEntity = ReceiveEntity::create(
            $group,
            50,
            CustomerEntity::create('bruno costa', null, null),
            1,
            '2022-01-01',
            null,
            0,
            null,
            $id,
        );

        /** @var Repo|Mockery\MockInterface */
        $mock = Mockery::mock(stdClass::class, Repo::class);
        $mock->shouldReceive('find')->andReturn($objEntity);
        $mock->shouldReceive('update')->andReturn(true);

        /** @var PaymentRepositoryInterface|Mockery\MockInterface */
        $mockPayment = Mockery::mock(stdClass::class, PaymentRepositoryInterface::class);
        $mockPayment->shouldReceive('insert')->andReturn(true);

        /** @var BankAccountRepositoryInterface|Mockery\MockInterface */
        $mockBankAccount = Mockery::mock(stdClass::class, BankAccountRepositoryInterface::class);

        /** @var AccountRepositoryInterface|Mockery\MockInterface */
        $mockAccount = Mockery::mock(stdClass::class, AccountRepositoryInterface::class);
        $mockAccount->shouldReceive('find')->andReturn(AccountEntity::create(new EntityObject($id, $objEntity), 0));

        /** @var TransactionInterface|Mockery\MockInterface */
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mockTransaction->shouldReceive('commit');
        $mockTransaction->shouldReceive('rollback');

        /** @var PublishManagerInterface|Mockery\MockInterface */
        $mockEvent = Mockery::mock(stdClass::class, PublishManagerInterface::class);
        $mockEvent->shouldReceive('dispatch');

        $uc = new PayUseCase(
            repo: $mock,
            payment: $mockPayment,
            bankAccount: $mockBankAccount,
            transaction: $mockTransaction,
            account: $mockAccount,
            event: $mockEvent,
        );

        $ret = $uc->handle(new PayInput($id, 50, 25, date('Y-m-d', strtotime('+1 day'))));
        $this->assertEquals(1, $ret->status);
        $this->assertInstanceOf(PayOutput::class, $ret);
        $mock->shouldHaveReceived('update')->times(1);
        $mockPayment->shouldHaveReceived('insert')->times(1);
        $mockEvent->shouldHaveReceived('dispatch')->times(1);
        $this->assertFalse($ret->completed);
    }
}
