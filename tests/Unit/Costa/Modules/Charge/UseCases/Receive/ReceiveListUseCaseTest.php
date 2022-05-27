<?php

namespace Tests\Unit\Costa\Modules\Charge\UseCases\Receive;

use Costa\Modules\Charge\UseCases\Receive\ReceiveListUseCase as UseCase;
use Costa\Modules\Charge\UseCases\Charge\DTO\List\{Input, Output};
use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Costa\Modules\Charge\Repository\MockChargeReceiveRepositoryInterfaceTrait;
use Tests\Unit\Costa\Shareds\Contracts\MockPaginationInterfaceTrait;

class ReceiveListUseCaseTest extends TestCase
{
    use MockChargeReceiveRepositoryInterfaceTrait, MockPaginationInterfaceTrait;

    public function testExec()
    {
        $repo = $this->mockChargeReceiveRepositoryInterface();
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
