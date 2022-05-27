<?php

namespace Tests\Unit\Costa\Modules\Relationship\UseCases\Supplier;

use Costa\Modules\Relationship\UseCases\Supplier\SupplierDeleteUseCase as UseCase;
use Costa\Modules\Relationship\UseCases\Supplier\DTO\Find\Input;
use Costa\Shareds\ValueObjects\DeleteObject;
use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Costa\Modules\Relationship\Entities\MockSupplierEntityTrait;
use Tests\Unit\Costa\Modules\Relationship\Repository\MockSupplierRepositoryInterfaceTrait;
use Tests\Unit\Costa\Shareds\Contracts\MockTransactionContractTrait;

class SupplierDeleteUseCaseTest extends TestCase
{
    use MockSupplierRepositoryInterfaceTrait, MockTransactionContractTrait, MockSupplierEntityTrait;

    public function testExec()
    {
        $entity = $this->mockSupplierEntity();

        $repo = $this->mockSupplierRepositoryInterface();
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
