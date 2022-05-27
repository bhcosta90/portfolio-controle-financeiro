<?php

namespace Tests\Unit\Costa\Modules\Charge\UseCases\Recurrence;

use Costa\Modules\Charge\UseCases\Recurrence\RecurrenceUpdateUseCase as UseCase;
use Costa\Modules\Charge\UseCases\Recurrence\DTO\Update\Input;
use Costa\Modules\Charge\UseCases\Recurrence\DTO\Update\Output;
use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Costa\Modules\Charge\Entities\MockRecurrenceEntityTrait;
use Tests\Unit\Costa\Modules\Charge\Repository\MockRecurrenceRepositoryInterfaceTrait;
use Tests\Unit\Costa\Shareds\Contracts\MockTransactionContractTrait;

class RecurrenceUpdateUseCaseTest extends TestCase
{
    use MockRecurrenceRepositoryInterfaceTrait, MockTransactionContractTrait, MockRecurrenceEntityTrait;

    public function testExec()
    {
        $entity = $this->mockRecurrenceEntity();
        
        $entityEdit = $this->mockRecurrenceEntity(name: 'bruno costa', days: 10);
        $entity->shouldReceive('id')->andReturn((string) $entity->id);
        $entity->shouldReceive('update')->andReturn($entity);

        $repo = $this->mockRecurrenceRepositoryInterface();
        $repo->shouldReceive('find')->andReturn($entity);
        $repo->shouldReceive('update')->andReturn($entityEdit);

        $uc = new UseCase(
            repo: $repo,
        );

        /** @var Input */
        $mockInput = Mockery::mock(Input::class, [
            $entity->id,
            'bruno costa',
            0
        ]);
        $resp = $uc->exec($mockInput);
        
        $this->assertInstanceOf(Output::class, $resp);
        $this->assertEquals($entity->id, $resp->id);
    }
}
