<?php

namespace Tests\Unit\src\Application\Charge\Modules\Recurrence\UseCases;

use Core\Application\Charge\Modules\Recurrence\Domain\RecurrenceEntity;
use Core\Application\Charge\Modules\Recurrence\UseCases\DTO\Find\Output;
use Core\Application\Charge\Modules\Recurrence\UseCases\FindUseCase;
use Core\Shared\UseCases\Find\FindInput;
use Mockery;
use Tests\UnitCase as TestCase;
use Ramsey\Uuid\Uuid;

class FindUseCaseTest extends TestCase
{
    public function testHandle()
    {
        $uc = new FindUseCase(
            repository: $mockRepository = $this->mockRecurrenceRepository()
        );
        $objEntity = RecurrenceEntity::create(tenant: Uuid::uuid4(), name: 'test', days: 50, id: $id = Uuid::uuid4());


        /** @var FindInput */
        $mockInput = Mockery::mock(FindInput::class, [$id]);

        $ret = $this->mock(fn() => $uc->handle($mockInput), [
            [
                'mock' => $mockRepository,
                'action' => 'find',
                'return' => $objEntity,
            ]
        ]);
        $this->assertInstanceOf(Output::class, $ret);
        $this->assertNotEmpty($ret->id);
    }
}
