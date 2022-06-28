<?php

namespace Tests\Unit\src\Financial\Payment\UseCases;

use Core\Financial\Account\Domain\AccountEntity;
use Core\Financial\Account\Repository\AccountRepositoryInterface;
use Core\Financial\Payment\Domain\PaymentEntity;
use Core\Financial\Payment\Repository\PaymentRepositoryInterface;
use Core\Financial\Payment\UseCases\ExecuteUseCase;
use Core\Financial\Payment\UseCases\DTO\Execute\{ExecuteInput, ExecuteOutput};
use Core\Shared\Interfaces\TransactionInterface;
use Core\Shared\ValueObjects\EntityObject;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class ExecuteUseCaseTest extends TestCase
{
    public function testHandleEmpty()
    {
        /** @var PaymentRepositoryInterface|Mockery\MockInterface */
        $mockPayment = Mockery::mock(stdClass::class, PaymentRepositoryInterface::class);
        $mockPayment->shouldReceive('findPaymentExecuteByDate')->andReturn([]);

        /** @var AccountRepositoryInterface|Mockery\MockInterface */
        $mockAccount = Mockery::mock(stdClass::class, AccountRepositoryInterface::class);

        /** @var TransactionInterface|Mockery\MockInterface */
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);

        $uc = new ExecuteUseCase(
            payment: $mockPayment,
            account: $mockAccount,
            transaction: $mockTransaction
        );

        $ret = $uc->handle(new ExecuteInput(date('Y-m-d')));
        $this->assertInstanceOf(ExecuteOutput::class, $ret);
        $this->assertCount(0, $ret->data);
    }

    public function testHandleOnePayment()
    {
        /** @var PaymentRepositoryInterface|Mockery\MockInterface */
        $mockPayment = Mockery::mock(stdClass::class, PaymentRepositoryInterface::class);
        $mockPayment->shouldReceive('findPaymentExecuteByDate')->andReturn([
            $objEntity = $this->getEntity(date: date('Y-m-d')),
        ]);
        $mockPayment->shouldReceive('update')->andReturn(true);

        /** @var AccountRepositoryInterface|Mockery\MockInterface */
        $mockAccount = Mockery::mock(stdClass::class, AccountRepositoryInterface::class);

        /** @var TransactionInterface|Mockery\MockInterface */
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mockTransaction->shouldReceive('rollback');
        $mockTransaction->shouldReceive('commit');

        $uc = new ExecuteUseCase(
            payment: $mockPayment,
            account: $mockAccount,
            transaction: $mockTransaction
        );

        $ret = $uc->handle(new ExecuteInput(date('Y-m-d')));
        $this->assertInstanceOf(ExecuteOutput::class, $ret);
        $this->assertCount(1, $ret->data);
        $this->assertEquals(2, $objEntity->status->value);
    }

    public function testHandleOnePaymentTwoCharges()
    {
        /** @var PaymentRepositoryInterface|Mockery\MockInterface */
        $mockPayment = Mockery::mock(stdClass::class, PaymentRepositoryInterface::class);
        $mockPayment->shouldReceive('findPaymentExecuteByDate')->andReturn([
            $this->getEntity(date: date('Y-m-d')),
            $this->getEntity(date: date('Y-m-d', strtotime('+1 day'))),
        ]);
        $mockPayment->shouldReceive('update')->andReturn(true);

        /** @var AccountRepositoryInterface|Mockery\MockInterface */
        $mockAccount = Mockery::mock(stdClass::class, AccountRepositoryInterface::class);

        /** @var TransactionInterface|Mockery\MockInterface */
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mockTransaction->shouldReceive('rollback');
        $mockTransaction->shouldReceive('commit');

        $uc = new ExecuteUseCase(
            payment: $mockPayment,
            account: $mockAccount,
            transaction: $mockTransaction
        );

        $ret = $uc->handle(new ExecuteInput(date('Y-m-d')));
        $this->assertInstanceOf(ExecuteOutput::class, $ret);
        $this->assertCount(1, $ret->data);
    }

    public function testHandleTwoPayments()
    {
        /** @var PaymentRepositoryInterface|Mockery\MockInterface */
        $mockPayment = Mockery::mock(stdClass::class, PaymentRepositoryInterface::class);
        $mockPayment->shouldReceive('findPaymentExecuteByDate')->andReturn([
            $this->getEntity(date: date('Y-m-d')),
            $this->getEntity(date: date('Y-m-d')),
        ]);
        $mockPayment->shouldReceive('update')->andReturn(true);

        /** @var AccountRepositoryInterface|Mockery\MockInterface */
        $mockAccount = Mockery::mock(stdClass::class, AccountRepositoryInterface::class);

        /** @var TransactionInterface|Mockery\MockInterface */
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mockTransaction->shouldReceive('rollback');
        $mockTransaction->shouldReceive('commit');

        $uc = new ExecuteUseCase(
            payment: $mockPayment,
            account: $mockAccount,
            transaction: $mockTransaction
        );

        $ret = $uc->handle(new ExecuteInput(date('Y-m-d')));
        $this->assertInstanceOf(ExecuteOutput::class, $ret);
        $this->assertCount(2, $ret->data);
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
