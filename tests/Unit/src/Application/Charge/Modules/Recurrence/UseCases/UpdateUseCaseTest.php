<?php

namespace Tests\Unit\src\Application\Charge\Modules\Recurrence\UseCases;

use Core\Application\Charge\Modules\Recurrence\Domain\RecurrenceEntity;
use Core\Application\Charge\Modules\Recurrence\Repository\RecurrenceRepository;
use Core\Application\Charge\Modules\Recurrence\UseCases\DTO\Update\{Input, Output};
use Core\Application\Charge\Modules\Recurrence\UseCases\UpdateUseCase;
use Mockery;
use Tests\UnitCase as TestCase;
use Ramsey\Uuid\Uuid;

class UpdateUseCaseTest extends TestCase
{
    public function testHandle()
    {
        $uc = new UpdateUseCase(
            repository: $mockRepository = $this->mockRecurrenceRepository()
        );
        $objEntity = RecurrenceEntity::create(tenant: Uuid::uuid4(), name: 'test', days: 50, id: $id = Uuid::uuid4());

        /** @var Input */
        $mockInput = Mockery::mock(Input::class, [$id, 'test', 50]);

        $ret = $this->mock(fn() => $uc->handle($mockInput), [
            [
                'mock' => $mockRepository,
                'action' => 'find',
                'return' => $objEntity,
            ],
            [
                'mock' => $mockRepository,
                'action' => 'update',
                'return' => true,
            ]
        ]);
        $this->assertInstanceOf(Output::class, $ret);
        $this->assertNotEmpty($ret->id);
    }
}
