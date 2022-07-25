<?php

namespace Tests\Unit\src\Application\Charge\Modules\Recurrence\UseCases;

use Core\Application\Charge\Modules\Recurrence\UseCases\CreateUseCase;
use Core\Application\Charge\Modules\Recurrence\UseCases\DTO\Create\{Input, Output};
use Mockery;
use Ramsey\Uuid\Uuid;
use Tests\UnitCase as TestCase;

class CreateUseCaseTest extends TestCase
{
    public function testHandle()
    {
        $uc = new CreateUseCase(
            repository: $mockRepository = $this->mockRecurrenceRepository()
        );

        /** @var Input */
        $mockInput = Mockery::mock(Input::class, [Uuid::uuid4(), 'test', 30]);

        $ret = $this->mock(fn() => $uc->handle($mockInput), [
            [
                'mock' => $mockRepository,
                'action' => 'insert',
                'return' => true,
                'times' => 1,
            ]
        ]);
        $this->assertInstanceOf(Output::class, $ret);
        $this->assertNotEmpty($ret->id);
    }
}
