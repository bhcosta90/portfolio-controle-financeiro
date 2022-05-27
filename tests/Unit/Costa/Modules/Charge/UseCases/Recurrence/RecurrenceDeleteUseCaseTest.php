<?php

namespace Tests\Unit\Costa\Modules\Charge\UseCases\Recurrence;

use Costa\Modules\Charge\UseCases\Recurrence\RecurrenceDeleteUseCase as UseCase;
use Costa\Modules\Charge\UseCases\Recurrence\DTO\Find\Input;
use Costa\Shareds\ValueObjects\DeleteObject;
use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Costa\Modules\Charge\Entities\MockRecurrenceEntityTrait;
use Tests\Unit\Costa\Modules\Charge\Repository\MockRecurrenceRepositoryInterfaceTrait;

class RecurrenceDeleteUseCaseTest extends TestCase
{
    use MockRecurrenceRepositoryInterfaceTrait, MockRecurrenceEntityTrait, MockRecurrenceEntityTrait;

    public function testExec()
    {
        $entity = $this->mockRecurrenceEntity();

        $repo = $this->mockRecurrenceRepositoryInterface();
        $repo->shouldReceive('delete')->andReturn(true);

        $uc = new UseCase(
            repo: $repo,
        );

        /** @var Input */
        $mockInput = Mockery::mock(Input::class, [
            $entity->id
        ]);
        $resp = $uc->exec($mockInput);

        $this->assertInstanceOf(DeleteObject::class, $resp);
        $this->assertTrue($resp->success);

        $repo->shouldHaveReceived('delete')->times(limit: 1);
    }
}
