<?php

namespace Tests\Unit\Costa\Modules\Relationship\UseCases\Customer;

use Costa\Modules\Relationship\UseCases\Customer\CustomerUpdateUseCase as UseCase;
use Costa\Modules\Relationship\UseCases\Customer\DTO\Update\Input;
use Costa\Modules\Relationship\UseCases\Customer\DTO\Update\Output;
use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Costa\Modules\Relationship\Entities\MockCustomerEntityTrait;
use Tests\Unit\Costa\Modules\Relationship\Repository\MockCustomerRepositoryInterfaceTrait;
use Tests\Unit\Costa\Shareds\Contracts\MockTransactionContractTrait;

class CustomerUpdateUseCaseTest extends TestCase
{
    use MockCustomerRepositoryInterfaceTrait, MockTransactionContractTrait, MockCustomerEntityTrait;

    public function testExec()
    {
        $entity = $this->mockCustomerEntity();
        
        $entityEdit = $this->mockCustomerEntity(name: 'bruno costa', id: $entity->id);
        $entity->shouldReceive('id')->andReturn((string) $entity->id);
        $entity->shouldReceive('update')->andReturn($entity);

        $repo = $this->mockCustomerRepositoryInterface();
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
