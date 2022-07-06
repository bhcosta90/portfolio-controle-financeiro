<?php

namespace Tests\Unit\src\Application\Charge\Modules\Recurrence\Services;

use Core\Application\Charge\Modules\Recurrence\Repository\RecurrenceRepository;
use Core\Application\Charge\Modules\Recurrence\Services\CreateService;
use Core\Application\Charge\Modules\Recurrence\Services\DTO\Create\{Input, Output};
use Mockery;
use PHPUnit\Framework\TestCase;

class CreateServiceTest extends TestCase
{
    public function testHandle()
    {
        $uc = new CreateService(
            repository: $mockRepository = $this->mockRepository()
        );
        $mockRepository->shouldReceive('insert')->andReturn(true);

        /** @var Input */
        $mockInput = Mockery::mock(Input::class, ['test', 30]);

        $ret = $uc->handle($mockInput);
        $this->assertInstanceOf(Output::class, $ret);
        $this->assertNotEmpty($ret->id);

        $mockRepository->shouldHaveReceived('insert')->times(1);
    }

    private function mockRepository(): string|RecurrenceRepository|Mockery\MockInterface
    {
        return Mockery::mock(RecurrenceRepository::class);
    }
}
