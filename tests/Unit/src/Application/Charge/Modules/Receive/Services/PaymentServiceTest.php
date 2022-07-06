<?php

namespace Tests\Unit\src\Application\Charge\Modules\Receive\Services;

use Core\Application\AccountBank\Domain\AccountBankEntity;
use Core\Application\AccountBank\Events\AddValueEvent;
use Core\Application\AccountBank\Repository\AccountBankRepository;
use Core\Application\Charge\Modules\Receive\Domain\ReceiveEntity as Entity;
use Core\Application\Charge\Modules\Receive\Events\AddPayEvent;
use Core\Application\Charge\Modules\Receive\Repository\ChargeReceiveRepository as ChargeRepo;
use Core\Application\Charge\Modules\Receive\Services\PaymentService;
use Core\Application\Charge\Modules\Receive\Services\DTO\Payment\{Input, Output};
use Core\Application\Charge\Modules\Recurrence\Repository\RecurrenceRepository;
use Core\Application\Charge\Modules\Recurrence\Domain\RecurrenceEntity;
use Core\Application\Payment\Repository\PaymentRepository;
use Core\Application\Relationship\Modules\Company\Domain\CompanyEntity;
use Core\Application\Relationship\Modules\Customer\Repository\CustomerRepository as RepositoryRelationship;
use Core\Application\Relationship\Modules\Company\Events\RemoveValueEvent;
use Core\Shared\Interfaces\EventManagerInterface;
use Core\Shared\Interfaces\TransactionInterface;
use PHPUnit\Framework\TestCase;
use Mockery;
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

        $objRelationship = CompanyEntity::create(name: 'teste', id: Uuid::uuid4());
        $objRecurrence = RecurrenceEntity::create(name: 'teste', days: 30, id: Uuid::uuid4());
        $objAccountBank = AccountBankEntity::create('teste', 0);

        $mockRepository->shouldReceive('find')->andReturn($objEntity);
        $mockRepository->shouldReceive('update')->andReturn(true);
        $mockRepository->shouldReceive('insert')->andReturn(true);
        $mockPayment->shouldReceive('insert')->andReturn(true);
        $mockRelationship->shouldReceive('find')->andReturn($objRelationship);
        $mockRecurrence->shouldReceive('find')->andReturn($objRecurrence);
        $mockBank->shouldReceive('find')->andReturn($objAccountBank);

        $ret = $uc->handle(new Input(id: $objEntity->id(), valuePayment: 50, valueCharge: 50, idAccountBank: $objAccountBank->id()));
        $this->assertNotEmpty($ret->idCharge);
        $mockRepository->shouldHaveReceived('find')->times(1);
        $mockRepository->shouldHaveReceived('insert')->times(1);
        $mockPayment->shouldHaveReceived('insert')->times(1);
        $mockRecurrence->shouldHaveReceived('find')->times(1);
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

        $objRelationship = CompanyEntity::create(name: 'teste', id: Uuid::uuid4());
        $objAccountBank = AccountBankEntity::create('teste', 0);

        $mockRepository->shouldReceive('find')->andReturn($objEntity);
        $mockRepository->shouldReceive('update')->andReturn(true);
        $mockRepository->shouldReceive('insert')->andReturn(true);
        $mockPayment->shouldReceive('insert')->andReturn(true);
        $mockRelationship->shouldReceive('find')->andReturn($objRelationship);
        $mockBank->shouldReceive('find')->andReturn($objAccountBank);

        $ret = $uc->handle(new Input(id: $objEntity->id(), valuePayment: 50, valueCharge: 50, idAccountBank: $objAccountBank->id()));
        $this->assertNull($ret->idCharge);
        $mockRepository->shouldHaveReceived('find')->times(1);
        $mockRepository->shouldNotHaveReceived('insert');
        $mockPayment->shouldHaveReceived('insert')->times(1);
        $mockRecurrence->shouldNotHaveReceived('find');
    }

    private function mockRepository(): string|ChargeRepo|Mockery\MockInterface
    {
        return Mockery::mock(ChargeRepo::class);
    }

    private function mockRepositoryRelationship(): string|RepositoryRelationship|Mockery\MockInterface
    {
        return Mockery::mock(RepositoryRelationship::class);
    }

    private function mockRecurrenceRepository(): string|RecurrenceRepository|Mockery\MockInterface
    {
        return Mockery::mock(RecurrenceRepository::class);
    }

    private function mockAccountBankRepository(): string|AccountBankRepository|Mockery\MockInterface
    {
        return Mockery::mock(AccountBankRepository::class);
    }

    private function mockPayment(): string|PaymentRepository|Mockery\MockInterface
    {
        return Mockery::mock(PaymentRepository::class);
    }

    private function mockTransaction(): string|TransactionInterface|Mockery\MockInterface
    {
        /** @var Mockery\MockInterface */
        $mock = Mockery::mock(TransactionInterface::class);
        $mock->shouldReceive('commit');
        $mock->shouldReceive('rollback');
        return $mock;
    }
}
