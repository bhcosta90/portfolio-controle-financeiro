<?php

namespace Tests\Unit\Costa\Modules\Account\UseCases\Bank;

use Costa\Modules\Account\UseCases\Bank\BankListUseCase as UseCase;
use Costa\Modules\Account\UseCases\Bank\DTO\List\{Input, Output};
use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Costa\Modules\Account\Repository\MockBankRepositoryInterfaceTrait;
use Tests\Unit\Costa\Shareds\Contracts\MockPaginationInterfaceTrait;

class BankListUseCaseTest extends TestCase
{
    use MockBankRepositoryInterfaceTrait, MockPaginationInterfaceTrait;

    public function testExec()
    {
        $repo = $this->mockBankRepositoryInterface();
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
