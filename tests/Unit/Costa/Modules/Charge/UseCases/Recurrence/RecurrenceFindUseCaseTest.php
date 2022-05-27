<?php

namespace Tests\Unit\Costa\Modules\Charge\UseCases\Recurrence;

use Costa\Modules\Charge\UseCases\Recurrence\RecurrenceFindUseCase as UseCase;
use Costa\Modules\Charge\UseCases\Recurrence\DTO\Find\Input;
use Costa\Modules\Charge\UseCases\Recurrence\DTO\Find\Output;
use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Costa\Modules\Charge\Entities\MockRecurrenceEntityTrait;
use Tests\Unit\Costa\Modules\Charge\Repository\MockRecurrenceRepositoryInterfaceTrait;

class RecurrenceFindUseCaseTest extends TestCase
{
    use MockRecurrenceRepositoryInterfaceTrait, MockRecurrenceEntityTrait;

    public function testExec()
    {
        $entity = $this->mockRecurrenceEntity();
        $entity->shouldReceive('id')->andReturn((string) $entity->id);

        $repo = $this->mockRecurrenceRepositoryInterface();
        $repo->shouldReceive('find')->andReturn($entity);

        $uc = new UseCase(
            repo: $repo,
        );

        /** @var Input */
        $mockInput = Mockery::mock(Input::class, [
            $entity->id
        ]);
        $resp = $uc->exec($mockInput);

        $this->assertInstanceOf(Output::class, $resp);
        $this->assertEquals('test of customer', $resp->name);
        $this->assertNotEmpty($resp->id);        

        $repo->shouldHaveReceived('find')->times(limit: 1);
    }
}
