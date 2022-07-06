<?php

namespace Tests\Unit\src\Application\Charge\Modules\Recurrence\Services;

use Core\Application\Charge\Modules\Recurrence\Repository\RecurrenceRepository;
use Core\Application\Charge\Modules\Recurrence\Services\DeleteService;
use Core\Application\Charge\Modules\Recurrence\Domain\RecurrenceEntity;
use Core\Shared\UseCases\Delete\{DeleteInput, DeleteOutput};
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class DeleteServiceTest extends TestCase
{
    public function testHandle()
    {
        $uc = new DeleteService(
            repository: $mockRepository = $this->mockRepository()
        );
        $objEntity = RecurrenceEntity::create(tenant: Uuid::uuid4(), name: 'test', days: 50, id: $id = Uuid::uuid4());

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

    private function mockRepository(): string|RecurrenceRepository|Mockery\MockInterface
    {
        return Mockery::mock(RecurrenceRepository::class);
    }
}
