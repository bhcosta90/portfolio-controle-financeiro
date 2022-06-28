<?php

namespace Tests\Unit\src\Financial\Payment\UseCases;

use Core\Financial\Account\Domain\AccountEntity;
use Core\Financial\Account\Repository\AccountRepositoryInterface;
use Core\Financial\Payment\Domain\PaymentEntity;
use Core\Financial\Payment\Repository\PaymentRepositoryInterface;
use Core\Financial\Payment\UseCases\PaymentUseCase;
use Core\Financial\Payment\UseCases\DTO\Payment\{PaymentInput, PaymentOutput};
use Core\Shared\Interfaces\TransactionInterface;
use Core\Shared\ValueObjects\EntityObject;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class PaymentUseCaseTest extends TestCase
{
    public function testPaymentNotAccount()
    {
        $id = Uuid::uuid4();
        
        /** @var PaymentRepositoryInterface|Mockery\MockInterface */
        $mockPayment = Mockery::mock(stdClass::class, PaymentRepositoryInterface::class);
        $mockPayment->shouldReceive('find')->andReturn($this->getEntity());
        $mockPayment->shouldReceive('update')->andReturn(true);

        /** @var AccountRepositoryInterface|Mockery\MockInterface */
        $mockAccount = Mockery::mock(stdClass::class, AccountRepositoryInterface::class);

        /** @var TransactionInterface|Mockery\MockInterface */
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mockTransaction->shouldReceive('rollback');
        $mockTransaction->shouldReceive('commit');

        $uc = new PaymentUseCase(
            payment: $mockPayment,
            account: $mockAccount,
            transaction: $mockTransaction
        );

        $ret = $uc->handle(new PaymentInput(
            id: $id,
            value: 100,
            accountFromId: null,
            accountToId: null,
        ));
        $this->assertInstanceOf(PaymentOutput::class, $ret);
        $mockPayment->shouldHaveReceived('find');
        $mockPayment->shouldHaveReceived('update')->times(1);
        $mockAccount->shouldNotHaveReceived('add');
    }

    public function testPaymentAccountFrom()
    {
        $id = Uuid::uuid4();
        $idAccountFrom = Uuid::uuid4();
        
        /** @var PaymentRepositoryInterface|Mockery\MockInterface */
        $mockPayment = Mockery::mock(stdClass::class, PaymentRepositoryInterface::class);
        $mockPayment->shouldReceive('find')->andReturn($this->getEntity(accountFrom: $idAccountFrom));
        $mockPayment->shouldReceive('update')->andReturn(true);

        /** @var AccountRepositoryInterface|Mockery\MockInterface */
        $mockAccount = Mockery::mock(stdClass::class, AccountRepositoryInterface::class);
        $mockAccount->shouldReceive('sub')->andReturn(true);

        /** @var TransactionInterface|Mockery\MockInterface */
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mockTransaction->shouldReceive('rollback');
        $mockTransaction->shouldReceive('commit');

        $uc = new PaymentUseCase(
            payment: $mockPayment,
            account: $mockAccount,
            transaction: $mockTransaction
        );

        $ret = $uc->handle(new PaymentInput(
            id: $id,
            value: 100,
            accountFromId: $idAccountFrom,
            accountToId: null,
        ));
        $this->assertInstanceOf(PaymentOutput::class, $ret);
        $mockPayment->shouldHaveReceived('find');
        $mockPayment->shouldHaveReceived('update')->times(1);
        $mockAccount->shouldNotHaveReceived('add');
        $mockAccount->shouldHaveReceived('sub')->times(1);
    }

    public function testPaymentAccountTo()
    {
        $id = Uuid::uuid4();
        $idAccountTo = Uuid::uuid4();
        
        /** @var PaymentRepositoryInterface|Mockery\MockInterface */
        $mockPayment = Mockery::mock(stdClass::class, PaymentRepositoryInterface::class);
        $mockPayment->shouldReceive('find')->andReturn($this->getEntity(accountTo: $idAccountTo));
        $mockPayment->shouldReceive('update')->andReturn(true);

        /** @var AccountRepositoryInterface|Mockery\MockInterface */
        $mockAccount = Mockery::mock(stdClass::class, AccountRepositoryInterface::class);
        $mockAccount->shouldReceive('add')->andReturn(true);

        /** @var TransactionInterface|Mockery\MockInterface */
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mockTransaction->shouldReceive('rollback');
        $mockTransaction->shouldReceive('commit');

        $uc = new PaymentUseCase(
            payment: $mockPayment,
            account: $mockAccount,
            transaction: $mockTransaction
        );

        $ret = $uc->handle(new PaymentInput(
            id: $id,
            value: 100,
            accountFromId: null,
            accountToId: $idAccountTo,
        ));
        $this->assertInstanceOf(PaymentOutput::class, $ret);
        $mockPayment->shouldHaveReceived('find');
        $mockPayment->shouldHaveReceived('update')->times(1);
        $mockAccount->shouldNotHaveReceived('sub');
        $mockAccount->shouldHaveReceived('add')->times(1);
    }

    public function testPaymentAccountAll()
    {
        $id = Uuid::uuid4();
        $idAccountFrom = Uuid::uuid4();
        $idAccountTo = Uuid::uuid4();
        
        /** @var PaymentRepositoryInterface|Mockery\MockInterface */
        $mockPayment = Mockery::mock(stdClass::class, PaymentRepositoryInterface::class);
        $mockPayment->shouldReceive('find')->andReturn($this->getEntity(
            accountTo: $idAccountTo,
            accountFrom: $idAccountFrom,
        ));
        $mockPayment->shouldReceive('update')->andReturn(true);

        /** @var AccountRepositoryInterface|Mockery\MockInterface */
        $mockAccount = Mockery::mock(stdClass::class, AccountRepositoryInterface::class);
        $mockAccount->shouldReceive('add')->andReturn(true);
        $mockAccount->shouldReceive('sub')->andReturn(true);

        /** @var TransactionInterface|Mockery\MockInterface */
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mockTransaction->shouldReceive('rollback');
        $mockTransaction->shouldReceive('commit');

        $uc = new PaymentUseCase(
            payment: $mockPayment,
            account: $mockAccount,
            transaction: $mockTransaction
        );

        $ret = $uc->handle(new PaymentInput(
            id: $id,
            value: 100,
            accountFromId: $idAccountFrom,
            accountToId: $idAccountTo,
        ));
        $this->assertInstanceOf(PaymentOutput::class, $ret);
        $mockPayment->shouldHaveReceived('find');
        $mockPayment->shouldHaveReceived('update')->times(1);
        $mockAccount->shouldHaveReceived('add')->times(1);
        $mockAccount->shouldHaveReceived('sub')->times(1);
    }

    protected function getEntity(
        $value = 50,
        $date = null,
        $accountFrom = null,
        $accountTo = null,
        $id = null,
    ) {
        return PaymentEntity::create(
            $value,
            $date ?: date('Y-m-d'),
            new EntityObject('1', 'test'),
            $accountFrom ? AccountEntity::create(new EntityObject('2', 'test'), 0, $accountFrom) : null,
            $accountTo ? AccountEntity::create(new EntityObject('2', 'test'), 0, $accountTo) : null,
            $id ?: Uuid::uuid4(),
        );
    }
}
