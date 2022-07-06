<?php

namespace Tests\Unit\src\Application\Charge\Modules\Receive\Services;

use Core\Application\Charge\Modules\Receive\Repository\ChargeReceiveRepository as ChargeRepo;
use Core\Application\Charge\Modules\Receive\Services\DeleteService;
use Core\Application\Charge\Modules\Receive\Domain\ReceiveEntity as Entity;
use Core\Application\Charge\Modules\Recurrence\Domain\RecurrenceEntity;
use Core\Application\Charge\Modules\Recurrence\Repository\RecurrenceRepository;
use Core\Shared\UseCases\Delete\{DeleteInput, DeleteOutput};
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class DeleteServiceTest extends TestCase
{
    public function testHandle()
    {
        $uc = new DeleteService(
            repository: $mockRepository = $this->mockRepository(),
            recurrence: $this->mockRecurrenceRepository(),
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
            id: $id = Uuid::uuid4()
        );

        $mockRepository->shouldReceive('find')->andReturn($objEntity);
        $mockRepository->shouldReceive('delete')->andReturn(true);

        /** @var DeleteInput */
        $mockInput = Mockery::mock(DeleteInput::class, [$id]);

        $ret = $uc->handle($mockInput);
        $this->assertInstanceOf(DeleteOutput::class, $ret);
        $this->assertTrue($ret->success);

        $mockRepository->shouldHaveReceived('find')->times(1);
        $mockRepository->shouldHaveReceived('delete')->times(1);
    }
    public function testHandleWithChargePay()
    {
        $uc = new DeleteService(
            repository: $mockRepository = $this->mockRepository(),
            recurrence: $this->mockRecurrenceRepository(),
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
            id: $id = Uuid::uuid4()
        );

        $mockRepository->shouldReceive('find')->andReturn($objEntity);
        $mockRepository->shouldReceive('delete')->andReturn(true);

        /** @var DeleteInput */
        $mockInput = Mockery::mock(DeleteInput::class, [$id]);

        $ret = $uc->handle($mockInput);
        $this->assertInstanceOf(DeleteOutput::class, $ret);
        $this->assertTrue($ret->success);

        $mockRepository->shouldHaveReceived('find')->times(1);
        $mockRepository->shouldHaveReceived('delete')->times(1);
    }

    private function mockRepository(): string|ChargeRepo|Mockery\MockInterface
    {
        return Mockery::mock(ChargeRepo::class);
    }

    private function mockRecurrenceRepository(): string|RecurrenceRepository|Mockery\MockInterface
    {
        /** @var Mockery\MockInterface */
        $mock = Mockery::mock(RecurrenceRepository::class);
        $mock->shouldReceive('find')->andReturn(RecurrenceEntity::create(Uuid::uuid4(), 'teste', 30));
        return $mock;
    }
}
