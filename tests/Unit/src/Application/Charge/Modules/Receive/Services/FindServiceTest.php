<?php

namespace Tests\Unit\src\Application\Charge\Modules\Receive\Services;

use Core\Application\Charge\Modules\Receive\Repository\ChargeReceiveRepository as ChargeRepo;
use Core\Application\Charge\Modules\Receive\Services\FindService;
use Core\Application\Charge\Modules\Receive\Services\DTO\Find\Output;
use Core\Application\Charge\Modules\Receive\Domain\ReceiveEntity as Entity;
use Core\Application\Relationship\Modules\Customer\Domain\CustomerEntity;
use Core\Application\Relationship\Modules\Customer\Repository\CustomerRepository;
use Core\Shared\UseCases\Find\FindInput;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class FindServiceTest extends TestCase
{
    public function testHandle()
    {
        $uc = new FindService(
            repository: $mockRepository = $this->mockRepository(),
            relationship: $mockRelationship = $this->mockRelationship(),
        );
        
        $objEntity = Entity::create(
            title: 'teste',
            resume: null,
            customer: Uuid::uuid4(),
            recurrence: Uuid::uuid4(),
            value: 50,
            pay: null,
            group: Uuid::uuid4(),
            date: '2022-01-01',
            id: $id = Uuid::uuid4()
        );

        $mockRepository->shouldReceive('find')->andReturn($objEntity);
        $mockRepository->shouldReceive('Find')->andReturn(true);

        /** @var FindInput */
        $mockInput = Mockery::mock(FindInput::class, [$id]);

        $ret = $uc->handle($mockInput);
        $this->assertInstanceOf(Output::class, $ret);
        $this->assertNotEmpty($ret->id);

        $mockRepository->shouldHaveReceived('find')->times(1);
        $mockRelationship->shouldHaveReceived('find')->times(1);
    }

    private function mockRepository(): string|ChargeRepo|Mockery\MockInterface
    {
        return Mockery::mock(ChargeRepo::class);
    }

    private function mockRelationship(): string|CustomerRepository|Mockery\MockInterface
    {
        /** @var Mockery\MockInterface */
        $mock = Mockery::mock(CustomerRepository::class);
        $mock->shouldReceive('find')->andReturn(CustomerEntity::create('teste'));
        return $mock;
    }
}
