<?php

namespace Tests\Unit\Costa\Modules\Relationship\UseCases\Customer;

use Costa\Modules\Relationship\UseCases\Customer\CustomerFindUseCase as UseCase;
use Costa\Modules\Relationship\UseCases\Customer\DTO\Find\Input;
use Costa\Modules\Relationship\UseCases\Customer\DTO\Find\Output;
use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Costa\Modules\Relationship\Entities\MockCustomerEntityTrait;
use Tests\Unit\Costa\Modules\Relationship\Repository\MockCustomerRepositoryInterfaceTrait;

class CustomerFindUseCaseTest extends TestCase
{
    use MockCustomerRepositoryInterfaceTrait, MockCustomerEntityTrait;

    public function testExec()
    {
        $entity = $this->mockCustomerEntity();
        $entity->shouldReceive('id')->andReturn((string) $entity->id);

        $repo = $this->mockCustomerRepositoryInterface();
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
        $this->assertEquals('test of customer', $resp->name);
        $this->assertNotEmpty($resp->id);        

        $repo->shouldHaveReceived('find')->times(limit: 1);
    }
}
