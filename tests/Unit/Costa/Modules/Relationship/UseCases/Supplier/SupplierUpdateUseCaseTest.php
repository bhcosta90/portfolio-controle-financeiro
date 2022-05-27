<?php

namespace Tests\Unit\Costa\Modules\Relationship\UseCases\Supplier;

use Costa\Modules\Relationship\UseCases\Supplier\SupplierUpdateUseCase as UseCase;
use Costa\Modules\Relationship\UseCases\Supplier\DTO\Update\Input;
use Costa\Modules\Relationship\UseCases\Supplier\DTO\Update\Output;
use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Costa\Modules\Relationship\Entities\MockSupplierEntityTrait;
use Tests\Unit\Costa\Modules\Relationship\Repository\MockSupplierRepositoryInterfaceTrait;
use Tests\Unit\Costa\Shareds\Contracts\MockTransactionContractTrait;

class SupplierUpdateUseCaseTest extends TestCase
{
    use MockSupplierRepositoryInterfaceTrait, MockTransactionContractTrait, MockSupplierEntityTrait;

    public function testExec()
    {
        $entity = $this->mockSupplierEntity();
        
        $entityEdit = $this->mockSupplierEntity(name: 'bruno costa', id: $entity->id);
        $entity->shouldReceive('id')->andReturn((string) $entity->id);
        $entity->shouldReceive('update')->andReturn($entity);

        $repo = $this->mockSupplierRepositoryInterface();
        $repo->shouldReceive('find')->andReturn($entity);
        $repo->shouldReceive('update')->andReturn($entityEdit);
        
        $uc = new UseCase(
            repo: $repo,
            transaction: $this->mockTransactionContract(),
        );

        /** @var Input */
        $mockInput = Mockery::mock(Input::class, [
            $entity->id,
            'bruno costa'
        ]);
        $resp = $uc->exec($mockInput);
        
        $this->assertInstanceOf(Output::class, $resp);
        $this->assertEquals($entity->id, $resp->id);
    }
}
