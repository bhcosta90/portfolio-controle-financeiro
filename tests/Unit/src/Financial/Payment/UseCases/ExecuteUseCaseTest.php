<?php

namespace Tests\Unit\src\Financial\Payment\UseCases;

use Core\Financial\Account\Domain\AccountEntity;
use Core\Financial\Account\Repository\AccountRepositoryInterface;
use Core\Financial\Payment\Domain\PaymentEntity;
use Core\Financial\Payment\Repository\PaymentRepositoryInterface;
use Core\Financial\Payment\UseCases\ExecuteUseCase;
use Core\Financial\Payment\UseCases\DTO\Execute\{ExecuteInput, ExecuteOutput};
use Core\Shared\Interfaces\EventManagerInterface;
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

        /** @var EventManagerInterface|Mockery\MockInterface */
        $mockEvent = Mockery::mock(stdClass::class, EventManagerInterface::class);

        $uc = new ExecuteUseCase(
            payment: $mockPayment,
            event: $mockEvent
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
            $this->getEntity(date: date('Y-m-d')),
        ]);
        $mockPayment->shouldReceive('update')->andReturn(true);

        /** @var EventManagerInterface|Mockery\MockInterface */
        $mockEvent = Mockery::mock(stdClass::class, EventManagerInterface::class);
        $mockEvent->shouldReceive('dispatch');

        $uc = new ExecuteUseCase(
            payment: $mockPayment,
            event: $mockEvent
        );

        $ret = $uc->handle(new ExecuteInput(date('Y-m-d')));
        $this->assertInstanceOf(ExecuteOutput::class, $ret);
        $this->assertCount(1, $ret->data);
    }

    public function testHandleTwoPayment()
    {
        /** @var PaymentRepositoryInterface|Mockery\MockInterface */
        $mockPayment = Mockery::mock(stdClass::class, PaymentRepositoryInterface::class);
        $mockPayment->shouldReceive('findPaymentExecuteByDate')->andReturn([
            $this->getEntity(date: date('Y-m-d')),
            $this->getEntity(date: date('Y-m-d')),
        ]);
        $mockPayment->shouldReceive('update')->andReturn(true);

        /** @var EventManagerInterface|Mockery\MockInterface */
        $mockEvent = Mockery::mock(stdClass::class, EventManagerInterface::class);
        $mockEvent->shouldReceive('dispatch');

        $uc = new ExecuteUseCase(
            payment: $mockPayment,
            event: $mockEvent
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
