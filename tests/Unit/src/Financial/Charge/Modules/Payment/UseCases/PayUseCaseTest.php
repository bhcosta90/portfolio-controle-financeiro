<?php

namespace Tests\Unit\src\Financial\Charge\Modules\Payment\UseCases;

use Core\Financial\BankAccount\Repository\BankAccountRepositoryInterface;
use Core\Financial\Charge\Modules\Payment\Domain\PaymentEntity;
use Mockery;
use PHPUnit\Framework\TestCase;
use Core\Financial\Charge\Modules\Payment\UseCases\PayUseCase;
use Core\Financial\Charge\Modules\Payment\UseCases\DTO\Pay\{PayInput, PayOutput};
use Core\Financial\Charge\Modules\Payment\Repository\PaymentRepositoryInterface as Repo;
use Core\Financial\Payment\Repository\PaymentRepositoryInterface;
use Core\Financial\Relationship\Modules\Company\Domain\CompanyEntity;
use Core\Shared\Interfaces\TransactionInterface;
use Ramsey\Uuid\Uuid;

class PayUseCaseTest extends TestCase
{
    public function testHandle()
    {
        $group = Uuid::uuid4();
        $id = Uuid::uuid4();

        $objEntity = PaymentEntity::create(
            $group,
            50,
            CompanyEntity::create('bruno costa', null, null),
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

        /** @var TransactionInterface|Mockery\MockInterface */
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mockTransaction->shouldReceive('commit');
        $mockTransaction->shouldReceive('rollback');

        $uc = new PayUseCase(
            repo: $mock,
            payment: $mockPayment,
            account: $mockBankAccount,
            transaction: $mockTransaction,
        );

        $ret = $uc->handle(new PayInput($id, 50, 25, date('Y-m-d')));
        $this->assertInstanceOf(PayOutput::class, $ret);
        $mock->shouldHaveReceived('update')->times(1);
        $mockPayment->shouldHaveReceived('insert')->times(1);
        $this->assertTrue($ret->completed);
    }

    public function testHandleNotComplete()
    {
        $group = Uuid::uuid4();
        $id = Uuid::uuid4();

        $objEntity = PaymentEntity::create(
            $group,
            50,
            CompanyEntity::create('bruno costa', null, null),
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

        /** @var TransactionInterface|Mockery\MockInterface */
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mockTransaction->shouldReceive('commit');
        $mockTransaction->shouldReceive('rollback');

        $uc = new PayUseCase(
            repo: $mock,
            payment: $mockPayment,
            account: $mockBankAccount,
            transaction: $mockTransaction
        );

        $ret = $uc->handle(new PayInput($id, 50, 25, date('Y-m-d', strtotime('+1 day'))));
        $this->assertEquals(1, $ret->status);
        $this->assertInstanceOf(PayOutput::class, $ret);
        $mock->shouldHaveReceived('update')->times(1);
        $mockPayment->shouldHaveReceived('insert')->times(1);
        $this->assertFalse($ret->completed);
    }
}
