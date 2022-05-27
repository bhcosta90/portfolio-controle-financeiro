<?php

namespace Tests\Unit\Costa\Modules\Charge\UseCases\Recurrence;

use Costa\Modules\Charge\UseCases\Recurrence\RecurrenceListUseCase as UseCase;
use Costa\Modules\Charge\UseCases\Recurrence\DTO\List\{Input, Output};
use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Costa\Modules\Charge\Repository\MockRecurrenceRepositoryInterfaceTrait;
use Tests\Unit\Costa\Shareds\Contracts\MockPaginationInterfaceTrait;

class RecurrenceCreateUseCaseTest extends TestCase
{
    use MockRecurrenceRepositoryInterfaceTrait, MockPaginationInterfaceTrait;
    
    public function testExec()
    {
        $repo = $this->mockRecurrenceRepositoryInterface();
        $repo->shouldReceive('paginate')->andReturn($this->mockPaginationInterface());
        
        $uc = new UseCase(
            repo: $repo,
        );

        /** @var Input */
        $mockInput = Mockery::mock(Input::class, []);
        $resp = $uc->exec($mockInput);
        
        $this->assertInstanceOf(Output::class, $resp);
        $repo->shouldHaveReceived('paginate')->times(limit: 1);
    }
}
