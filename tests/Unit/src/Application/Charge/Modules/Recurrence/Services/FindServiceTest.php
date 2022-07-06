<?php

namespace Tests\Unit\src\Application\Charge\Modules\Recurrence\Services;

use Core\Application\Charge\Modules\Recurrence\Repository\RecurrenceRepository;
use Core\Application\Charge\Modules\Recurrence\Services\FindService;
use Core\Application\Charge\Modules\Recurrence\Services\DTO\Find\Output;
use Core\Application\Charge\Modules\Recurrence\Domain\RecurrenceEntity;
use Core\Shared\UseCases\Find\FindInput;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class FindServiceTest extends TestCase
{
    public function testHandle()
    {
        $uc = new FindService(
            repository: $mockRepository = $this->mockRepository()
        );
        $objEntity = RecurrenceEntity::create(tenant: Uuid::uuid4(), name: 'test', days: 50, id: $id = Uuid::uuid4());

        $mockRepository->shouldReceive('find')->andReturn($objEntity);
        $mockRepository->shouldReceive('Find')->andReturn(true);

        /** @var FindInput */
        $mockInput = Mockery::mock(FindInput::class, [$id]);

        $ret = $uc->handle($mockInput);
        $this->assertInstanceOf(Output::class, $ret);
        $this->assertNotEmpty($ret->id);

        $mockRepository->shouldHaveReceived('find')->times(1);
    }

    private function mockRepository(): string|RecurrenceRepository|Mockery\MockInterface
    {
        return Mockery::mock(RecurrenceRepository::class);
    }
}
