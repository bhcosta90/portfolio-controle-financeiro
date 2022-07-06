<?php

namespace Tests\Unit\src\Application\Charge\Modules\Payment\Services;

use Core\Application\Charge\Modules\Payment\Repository\ChargePaymentRepository as ChargeRepo;
use Core\Application\Charge\Modules\Payment\Services\CreateService;
use Core\Application\Charge\Modules\Payment\Services\DTO\Create\{Input, Output};
use Core\Application\Charge\Modules\Recurrence\Repository\RecurrenceRepository;
use Core\Application\Relationship\Modules\Company\Repository\CompanyRepository as RepositoryRelationship;
use Core\Application\Relationship\Shared\Exceptions\RelationshipException;
use Core\Shared\Interfaces\TransactionInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CreateServiceTest extends TestCase
{
    public function testHandle()
    {
        $uc = new CreateService(
            repository: $mockRepository = $this->mockRepository(),
            relationship: $mockRelationship = $this->mockRepositoryRelationship(),
            recurrence: $mockRecurrence = $this->mockRecurrenceRepository(),
            transaction: $this->mockTransaction(),
        );

        $mockRecurrence->shouldReceive('exist')->andReturn(true);
        $mockRelationship->shouldReceive('exist')->andReturn(true);
        $mockRepository->shouldReceive('insertParcel')->andReturn(true);

        /** @var Input */
        $mockInput = Mockery::mock(Input::class, [
            'test',
            null,
            Uuid::uuid4(),
            Uuid::uuid4(),
            50,
            '2022-01-01',
        ]);

        $ret = $uc->handle($mockInput);
        $this->assertInstanceOf(Output::class, $ret[0]);
        $this->assertNotEmpty($ret[0]->id);

        $mockRelationship->shouldHaveReceived('exist')->times(1);
        $mockRecurrence->shouldHaveReceived('exist')->times(1);
        $mockRepository->shouldHaveReceived('insertParcel')->times(1);
    }

    public function testHandleRelationshipNotFound()
    {
        $this->expectException(RelationshipException::class);
        $this->expectExceptionMessage('Company not found');

        $uc = new CreateService(
            repository: $mockRepository = $this->mockRepository(),
            relationship: $mockRelationship = $this->mockRepositoryRelationship(),
            recurrence: $mockRecurrence = $this->mockRecurrenceRepository(),
            transaction: $this->mockTransaction(),
        );
        $mockRecurrence->shouldReceive('exist')->andReturn(true);
        $mockRelationship->shouldReceive('exist')->andReturn(false);
        $mockRepository->shouldReceive('insert')->andReturn(true);

        /** @var Input */
        $mockInput = Mockery::mock(Input::class, [
            'test',
            null,
            Uuid::uuid4(),
            50,
            1,
            '2022-01-01',
        ]);

        $uc->handle($mockInput);
    }

    public function testHandleRecurrenceNotFound()
    {
        $this->expectExceptionMessage('Recurrence not found');

        $uc = new CreateService(
            repository: $mockRepository = $this->mockRepository(),
            relationship: $mockRelationship = $this->mockRepositoryRelationship(),
            recurrence: $mockRecurrence = $this->mockRecurrenceRepository(),
            transaction: $this->mockTransaction(),
        );
        $mockRecurrence->shouldReceive('exist')->andReturn(false);
        $mockRelationship->shouldReceive('exist')->andReturn(true);
        $mockRepository->shouldReceive('insert')->andReturn(true);

        /** @var Input */
        $mockInput = Mockery::mock(Input::class, [
            'test',
            null,
            Uuid::uuid4(),
            50,
            1,
            '2022-01-01',
        ]);

        $uc->handle($mockInput);
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

    private function mockTransaction(): string|TransactionInterface|Mockery\MockInterface
    {
        /** @var Mockery\MockInterface */
        $mock = Mockery::mock(TransactionInterface::class);
        $mock->shouldReceive('commit');
        $mock->shouldReceive('rollback');
        return $mock;
    }
}
