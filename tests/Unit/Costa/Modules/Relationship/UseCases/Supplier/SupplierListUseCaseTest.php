<?php

namespace Tests\Unit\Costa\Modules\Relationship\UseCases\Supplier;

use Costa\Modules\Relationship\UseCases\Supplier\SupplierListUseCase as UseCase;
use Costa\Modules\Relationship\UseCases\Supplier\DTO\List\Input;
use Costa\Modules\Relationship\UseCases\Supplier\DTO\List\Output;
use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Costa\Modules\Relationship\Repository\MockSupplierRepositoryInterfaceTrait;
use Tests\Unit\Costa\Shareds\Contracts\MockPaginationInterfaceTrait;

class SupplierListUseCaseTest extends TestCase
{
    use MockSupplierRepositoryInterfaceTrait, MockPaginationInterfaceTrait;

    public function testExec()
    {
        $repo = $this->mockSupplierRepositoryInterface();
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
