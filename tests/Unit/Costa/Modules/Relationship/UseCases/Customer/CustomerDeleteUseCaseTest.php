<?php

namespace Tests\Unit\Costa\Modules\Relationship\UseCases\Customer;

use Costa\Modules\Relationship\UseCases\Customer\CustomerDeleteUseCase as UseCase;
use Costa\Modules\Relationship\UseCases\Customer\DTO\Find\Input;
use Costa\Shareds\ValueObjects\DeleteObject;
use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Costa\Modules\Relationship\Entities\MockCustomerEntityTrait;
use Tests\Unit\Costa\Modules\Relationship\Repository\MockCustomerRepositoryInterfaceTrait;
use Tests\Unit\Costa\Shareds\Contracts\MockTransactionContractTrait;

class CustomerDeleteUseCaseTest extends TestCase
{
    use MockCustomerRepositoryInterfaceTrait, MockTransactionContractTrait, MockCustomerEntityTrait;

    public function testExec()
    {
        $entity = $this->mockCustomerEntity();

        $repo = $this->mockCustomerRepositoryInterface();
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
