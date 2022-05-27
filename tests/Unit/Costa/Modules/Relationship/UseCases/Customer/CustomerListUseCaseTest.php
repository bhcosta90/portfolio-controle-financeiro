<?php

namespace Tests\Unit\Costa\Modules\Relationship\UseCases\Customer;

use Costa\Modules\Relationship\UseCases\Customer\CustomerListUseCase as UseCase;
use Costa\Modules\Relationship\UseCases\Customer\DTO\List\Input;
use Costa\Modules\Relationship\UseCases\Customer\DTO\List\Output;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;
use Tests\Unit\Costa\Modules\Relationship\Repository\MockCustomerRepositoryInterfaceTrait;
use Tests\Unit\Costa\Shareds\Contracts\MockPaginationInterfaceTrait;

class CustomerListUseCaseTest extends TestCase
{
    use MockCustomerRepositoryInterfaceTrait, MockPaginationInterfaceTrait;

    public function testExec()
    {
        $repo = $this->mockCustomerRepositoryInterface();
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
