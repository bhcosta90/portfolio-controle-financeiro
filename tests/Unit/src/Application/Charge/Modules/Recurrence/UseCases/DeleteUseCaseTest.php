<?php

namespace Tests\Unit\src\Application\Charge\Modules\Recurrence\UseCases;

use Core\Application\Charge\Modules\Recurrence\Domain\RecurrenceEntity;
use Core\Application\Charge\Modules\Recurrence\UseCases\DeleteUseCase;
use Core\Shared\UseCases\Delete\{DeleteInput, DeleteOutput};
use Mockery;
use Tests\UnitCase as TestCase;
use Ramsey\Uuid\Uuid;

class DeleteUseCaseTest extends TestCase
{
    public function testHandle()
    {
        $uc = new DeleteUseCase(
            repository: $mockRepository = $this->mockRecurrenceRepository()
        );
        $objEntity = RecurrenceEntity::create(tenant: Uuid::uuid4(), name: 'test', days: 50, id: $id = Uuid::uuid4());

        /** @var DeleteInput */
        $mockInput = Mockery::mock(DeleteInput::class, [$id]);

        $ret = $this->mock(fn() => $uc->handle($mockInput), [
            [
                'mock' => $mockRepository,
                'return' => $objEntity,
                'action' => 'find',
                'times' =>  1,
            ],
            [
                'mock' => $mockRepository,
                'return' => true,
                'action' => 'delete',
                'times' =>  1,
            ]
        ]);

        $this->assertInstanceOf(DeleteOutput::class, $ret);
        $this->assertTrue($ret->success);
    }
}
