<?php

namespace Tests\Unit\src\Application\Charge\Modules\Receive\Services;

use Core\Application\AccountBank\Domain\AccountBankEntity;
use Core\Application\AccountBank\Repository\AccountBankRepository;
use Core\Application\Charge\Modules\Receive\Domain\ReceiveEntity as Entity;
use Core\Application\Charge\Modules\Receive\Repository\ChargeReceiveRepository as ChargeRepo;
use Core\Application\Charge\Modules\Receive\Services\DTO\Payment\{Input};
use Core\Application\Charge\Modules\Receive\Services\PaymentService;
use Core\Application\Charge\Modules\Recurrence\Domain\RecurrenceEntity;
use Core\Application\Charge\Modules\Recurrence\Repository\RecurrenceRepository;
use Core\Application\Payment\Repository\PaymentRepository;
use Core\Application\Relationship\Modules\Company\Domain\CompanyEntity;
use Core\Application\Relationship\Modules\Customer\Repository\CustomerRepository as RepositoryRelationship;
use Core\Shared\Interfaces\TransactionInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class PaymentServiceTest extends TestCase
{
    public function testPaymentWithRecurrence()
    {
        $uc = new PaymentService(
            repository: $mockRepository = $this->mockRepository(),
            transaction: $this->mockTransaction(),
            relationship: $mockRelationship = $this->mockRepositoryRelationship(),
            bank: $mockBank = $this->mockAccountBankRepository(),
            recurrence: $mockRecurrence = $this->mockRecurrenceRepository(),
            payment: $mockPayment = $this->mockPayment(),
        );

        $objEntity = Entity::create(
            tenant: Uuid::uuid4(),
            title: 'teste',
            resume: null,
            customer: Uuid::uuid4(),
            recurrence: Uuid::uuid4(),
            value: 50,
            pay: null,
            group: Uuid::uuid4(),
            date: '2022-01-01',
            id: Uuid::uuid4()
        );

        $objRelationship = CompanyEntity::create(tenant: Uuid::uuid4(), name: 'teste', id: Uuid::uuid4());
        $objRecurrence = RecurrenceEntity::create(tenant: Uuid::uuid4(), name: 'teste', days: 30, id: Uuid::uuid4());
        $objAccountBank = AccountBankEntity::create(Uuid::uuid4(), 'teste', 0);

        $mockRepository->shouldReceive('find')->andReturn($objEntity);
        $mockRepository->shouldReceive('update')->andReturn(true);
        $mockRepository->shouldReceive('insert')->andReturn(true);
        $mockPayment->shouldReceive('insert')->andReturn(true);
        $mockRelationship->shouldReceive('find')->andReturn($objRelationship);
        $mockRecurrence->shouldReceive('get')->andReturn($objRecurrence);
        $mockBank->shouldReceive('find')->andReturn($objAccountBank);

        $ret = $uc->handle(new Input(
            id: $objEntity->id(),
            valuePayment: 50,
            idAccountBank: $objAccountBank->id(),
            newPayment: false,
            dateNewPayment: null,
        ));
        $this->assertNotEmpty($ret->idCharge);
        $mockRepository->shouldHaveReceived('find')->times(1);
        $mockRepository->shouldHaveReceived('insert')->times(1);
        $mockPayment->shouldHaveReceived('insert')->times(1);
        $mockRecurrence->shouldHaveReceived('get')->times(1);
    }

    private function mockRepository(): string|ChargeRepo|Mockery\MockInterface
    {
        return Mockery::mock(ChargeRepo::class);
    }

    private function mockTransaction(): string|TransactionInterface|Mockery\MockInterface
    {
        /** @var Mockery\MockInterface */
        $mock = Mockery::mock(TransactionInterface::class);
        $mock->shouldReceive('commit');
        $mock->shouldReceive('rollback');
        return $mock;
    }

    private function mockRepositoryRelationship(): string|RepositoryRelationship|Mockery\MockInterface
    {
        return Mockery::mock(RepositoryRelationship::class);
    }

    private function mockAccountBankRepository(): string|AccountBankRepository|Mockery\MockInterface
    {
        return Mockery::mock(AccountBankRepository::class);
    }

    private function mockRecurrenceRepository(): string|RecurrenceRepository|Mockery\MockInterface
    {
        return Mockery::mock(RecurrenceRepository::class);
    }

    private function mockPayment(): string|PaymentRepository|Mockery\MockInterface
    {
        return Mockery::mock(PaymentRepository::class);
    }

    public function testPaymentWithoutRecurrence()
    {
        $uc = new PaymentService(
            repository: $mockRepository = $this->mockRepository(),
            transaction: $this->mockTransaction(),
            relationship: $mockRelationship = $this->mockRepositoryRelationship(),
            bank: $mockBank = $this->mockAccountBankRepository(),
            recurrence: $mockRecurrence = $this->mockRecurrenceRepository(),
            payment: $mockPayment = $this->mockPayment(),
        );

        $objEntity = Entity::create(
            tenant: Uuid::uuid4(),
            title: 'teste',
            resume: null,
            customer: Uuid::uuid4(),
            recurrence: null,
            value: 50,
            pay: null,
            group: Uuid::uuid4(),
            date: '2022-01-01',
            id: Uuid::uuid4()
        );

        $objRelationship = CompanyEntity::create(tenant: Uuid::uuid4(), name: 'teste', id: Uuid::uuid4());
        $objAccountBank = AccountBankEntity::create(Uuid::uuid4(), 'teste', 0);

        $mockRepository->shouldReceive('find')->andReturn($objEntity);
        $mockRepository->shouldReceive('update')->andReturn(true);
        $mockRepository->shouldReceive('insert')->andReturn(true);
        $mockPayment->shouldReceive('insert')->andReturn(true);
        $mockRelationship->shouldReceive('find')->andReturn($objRelationship);
        $mockBank->shouldReceive('find')->andReturn($objAccountBank);

        $ret = $uc->handle(new Input(
            id: $objEntity->id(),
            valuePayment: 50,
            idAccountBank: $objAccountBank->id(),
            newPayment: false,
            dateNewPayment: null,
        ));
        $this->assertNull($ret->idCharge);
        $mockRepository->shouldHaveReceived('find')->times(1);
        $mockRepository->shouldNotHaveReceived('insert');
        $mockPayment->shouldHaveReceived('insert')->times(1);
        $mockRecurrence->shouldNotHaveReceived('find');
    }
}
