<?php

namespace Tests\Unit\Costa\Modules\Relationship\UseCases\Supplier;

use Costa\Modules\Relationship\UseCases\Supplier\SupplierFindUseCase as UseCase;
use Costa\Modules\Relationship\UseCases\Supplier\DTO\Find\Input;
use Costa\Modules\Relationship\UseCases\Supplier\DTO\Find\Output;
use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Costa\Modules\Relationship\Entities\MockSupplierEntityTrait;
use Tests\Unit\Costa\Modules\Relationship\Repository\MockSupplierRepositoryInterfaceTrait;

class SupplierFindUseCaseTest extends TestCase
{
    use MockSupplierRepositoryInterfaceTrait, MockSupplierEntityTrait;

    public function testExec()
    {
        $entity = $this->mockSupplierEntity();
        $entity->shouldReceive('id')->andReturn((string) $entity->id);

        $repo = $this->mockSupplierRepositoryInterface();
        $repo->shouldReceive('find')->andReturn($entity);

        $uc = new UseCase(
            repo: $repo,
        );

        /** @var Input */
        $mockInput = Mockery::mock(Input::class, [
            $entity->id
        ]);
        $resp = $uc->exec($mockInput);
        
        $this->assertInstanceOf(Output::class, $resp);
        $this->assertEquals('test of supplier', $resp->name);
        $this->assertNotEmpty($resp->id);        

        $repo->shouldHaveReceived('find')->times(limit: 1);
    }
}
