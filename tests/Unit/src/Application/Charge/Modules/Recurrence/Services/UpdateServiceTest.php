<?php

namespace Tests\Unit\src\Application\Charge\Modules\Recurrence\Services;

use Core\Application\Charge\Modules\Recurrence\Repository\RecurrenceRepository;
use Core\Application\Charge\Modules\Recurrence\Services\UpdateService;
use Core\Application\Charge\Modules\Recurrence\Services\DTO\Update\{Input, Output};
use Core\Application\Charge\Modules\Recurrence\Domain\RecurrenceEntity;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UpdateServiceTest extends TestCase
{
    public function testHandle()
    {
        $uc = new UpdateService(
            repository: $mockRepository = $this->mockRepository()
        );
        $objEntity = RecurrenceEntity::create(tenant: Uuid::uuid4(), name: 'test', days: 50, id: $id = Uuid::uuid4());

        $mockRepository->shouldReceive('find')->andReturn($objEntity);
        $mockRepository->shouldReceive('update')->andReturn(true);

        /** @var Input */
        $mockInput = Mockery::mock(Input::class, [$id, 'test', 50]);

        $ret = $uc->handle($mockInput);
        $this->assertInstanceOf(Output::class, $ret);
        $this->assertNotEmpty($ret->id);

        $mockRepository->shouldHaveReceived('find')->times(1);
        $mockRepository->shouldHaveReceived('update')->times(1);
    }

    private function mockRepository(): string|RecurrenceRepository|Mockery\MockInterface
    {
        return Mockery::mock(RecurrenceRepository::class);
    }
}
