<?php

namespace Tests\Unit\src\Application\Charge\Modules\Receive\Services;

use Core\Application\Charge\Modules\Receive\Repository\ChargeReceiveRepository as ChargeRepo;
use Core\Application\Charge\Modules\Receive\Services\UpdateService;
use Core\Application\Charge\Modules\Receive\Services\DTO\Update\{Input, Output};
use Core\Application\Charge\Modules\Receive\Domain\ReceiveEntity as Entity;
use Core\Application\Charge\Modules\Recurrence\Repository\RecurrenceRepository;
use Core\Application\Relationship\Modules\Customer\Repository\CustomerRepository as RepositoryRelationship;
use Core\Application\Relationship\Shared\Exceptions\RelationshipException;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UpdateServiceTest extends TestCase
{
    public function testHandle()
    {
        $uc = new UpdateService(
            repository: $mockRepository = $this->mockRepository(),
            relationship: $mockRelationship = $this->mockRepositoryRelationship(),
            recurrence: $mockRecurrence = $this->mockRecurrenceRepository(),
        );

        $mockRecurrence->shouldReceive('exist')->andReturn(true);
        $mockRelationship->shouldReceive('exist')->andReturn(true);

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
            id: $id = Uuid::uuid4()
        );

        $mockRepository->shouldReceive('find')->andReturn($objEntity);
        $mockRepository->shouldReceive('update')->andReturn(true);

        /** @var Input */
        $mockInput = Mockery::mock(Input::class, [
            $id,
            'test',
            null,
            Uuid::uuid4(),
            Uuid::uuid4(),
            50,
            '2022-01-01',
        ]);

        $ret = $uc->handle($mockInput);
        $this->assertInstanceOf(Output::class, $ret);
        $this->assertNotEmpty($ret->id);

        $mockRelationship->shouldHaveReceived('exist')->times(1);
        $mockRepository->shouldHaveReceived('find')->times(1);
        $mockRepository->shouldHaveReceived('update')->times(1);
    }

    public function testHandleCompanyEqual()
    {
        $uc = new UpdateService(
            repository: $mockRepository = $this->mockRepository(),
            relationship: $mockRelationship = $this->mockRepositoryRelationship(),
            recurrence: $mockRecurrence = $this->mockRecurrenceRepository(),
        );

        $mockRecurrence->shouldReceive('exist')->andReturn(true);
        $mockRelationship->shouldReceive('exist')->andReturn(true);

        $objEntity = Entity::create(
            tenant: Uuid::uuid4(),
            title: 'teste',
            resume: null,
            customer: $idCompany = Uuid::uuid4(),
            recurrence: Uuid::uuid4(),
            value: 50,
            pay: null,
            group: Uuid::uuid4(),
            date: '2022-01-01',
            id: $id = Uuid::uuid4()
        );

        $mockRepository->shouldReceive('find')->andReturn($objEntity);
        $mockRepository->shouldReceive('update')->andReturn(true);

        /** @var Input */
        $mockInput = Mockery::mock(Input::class, [
            $id,
            'test',
            null,
            $idCompany,
            Uuid::uuid4(),
            50,
            '2022-01-01',
        ]);

        $ret = $uc->handle($mockInput);
        $this->assertInstanceOf(Output::class, $ret);
        $this->assertNotEmpty($ret->id);

        $mockRelationship->shouldNotHaveReceived('exist');
        $mockRepository->shouldHaveReceived('find')->times(1);
        $mockRepository->shouldHaveReceived('update')->times(1);
    }

    public function testHandleRecurrenceEqual()
    {
        $uc = new UpdateService(
            repository: $mockRepository = $this->mockRepository(),
            relationship: $mockRelationship = $this->mockRepositoryRelationship(),
            recurrence: $mockRecurrence = $this->mockRecurrenceRepository(),
        );

        $mockRecurrence->shouldReceive('exist')->andReturn(true);
        $mockRelationship->shouldReceive('exist')->andReturn(true);

        $objEntity = Entity::create(
            tenant: Uuid::uuid4(),
            title: 'teste',
            resume: null,
            customer: Uuid::uuid4(),
            recurrence: $idRecurrence = Uuid::uuid4(),
            value: 50,
            pay: null,
            group: Uuid::uuid4(),
            date: '2022-01-01',
            id: $id = Uuid::uuid4()
        );

        $mockRepository->shouldReceive('find')->andReturn($objEntity);
        $mockRepository->shouldReceive('update')->andReturn(true);

        /** @var Input */
        $mockInput = Mockery::mock(Input::class, [
            $id,
            'test',
            null,
            Uuid::uuid4(),
            $idRecurrence,
            50,
            '2022-01-01',
        ]);

        $ret = $uc->handle($mockInput);
        $this->assertInstanceOf(Output::class, $ret);
        $this->assertNotEmpty($ret->id);

        $mockRecurrence->shouldNotReceive('exist');
        $mockRelationship->shouldHaveReceived('exist')->times(1);
        $mockRepository->shouldHaveReceived('find')->times(1);
        $mockRepository->shouldHaveReceived('update')->times(1);
    }

    public function testHandleCompanyNotFound()
    {
        $this->expectException(RelationshipException::class);
        $this->expectExceptionMessage('Customer not found');

        $uc = new UpdateService(
            repository: $mockRepository = $this->mockRepository(),
            relationship: $mockRelationship = $this->mockRepositoryRelationship(),
            recurrence: $this->mockRecurrenceRepository(),
        );

        $mockRelationship->shouldReceive('exist')->andReturn(false);

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
            id: $id = Uuid::uuid4()
        );

        $mockRepository->shouldReceive('find')->andReturn($objEntity);
        $mockRepository->shouldReceive('update')->andReturn(true);

        /** @var Input */
        $mockInput = Mockery::mock(Input::class, [
            $id,
            'test',
            null,
            Uuid::uuid4(),
            Uuid::uuid4(),
            50,
            '2022-01-01',
        ]);

        $ret = $uc->handle($mockInput);
        $this->assertInstanceOf(Output::class, $ret);
        $this->assertNotEmpty($ret->id);

        $mockRepository->shouldHaveReceived('find')->times(1);
        $mockRepository->shouldHaveReceived('update')->times(1);
    }

    public function testHandleRecurrenceNotFound()
    {
        $this->expectExceptionMessage('Recurrence not found');

        $uc = new UpdateService(
            repository: $mockRepository = $this->mockRepository(),
            relationship: $mockRelationship = $this->mockRepositoryRelationship(),
            recurrence: $mockRecurrence = $this->mockRecurrenceRepository(),
        );

        $mockRecurrence->shouldReceive('exist')->andReturn(false);
        $mockRelationship->shouldReceive('exist')->andReturn(true);

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
            id: $id = Uuid::uuid4()
        );

        $mockRepository->shouldReceive('find')->andReturn($objEntity);
        $mockRepository->shouldReceive('update')->andReturn(true);

        /** @var Input */
        $mockInput = Mockery::mock(Input::class, [
            $id,
            'test',
            null,
            Uuid::uuid4(),
            Uuid::uuid4(),
            50,
            '2022-01-01',
        ]);

        $ret = $uc->handle($mockInput);
        $this->assertInstanceOf(Output::class, $ret);
        $this->assertNotEmpty($ret->id);

        $mockRepository->shouldHaveReceived('find')->times(1);
        $mockRepository->shouldHaveReceived('update')->times(1);
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
}
