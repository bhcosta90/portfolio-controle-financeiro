<?php

namespace Tests\Unit\Costa\Modules\Charge\UseCases\Payment;

use Costa\Modules\Charge\UseCases\Payment\PaymentListUseCase as UseCase;
use Costa\Modules\Charge\UseCases\Charge\DTO\List\{Input, Output};
use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Costa\Modules\Charge\Repository\MockChargePaymentRepositoryInterfaceTrait;
use Tests\Unit\Costa\Shareds\Contracts\MockPaginationInterfaceTrait;

class PaymentListUseCaseTest extends TestCase
{
    use MockChargePaymentRepositoryInterfaceTrait, MockPaginationInterfaceTrait;

    public function testExec()
    {
        $repo = $this->mockChargePaymentRepositoryInterface();
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
