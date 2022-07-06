<?php

namespace Tests\Unit\src\Application\Relationship\Modules\Customer\Services;

use Core\Application\Relationship\Modules\Customer\Repository\CustomerRepository;
use Core\Application\Relationship\Modules\Customer\Services\FindService;
use Core\Application\Relationship\Modules\Customer\Services\DTO\Find\Output;
use Core\Application\Relationship\Modules\Customer\Domain\CustomerEntity;
use Core\Shared\UseCases\Find\FindInput;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class FindServiceTest extends TestCase
{
    public function testHandle()
    {
        $uc = new FindService(
            repository: $mockRepository = $this->mockRepository()
        );
        $objEntity = CustomerEntity::create(name:  'test', id: $id = Uuid::uuid4());

        $mockRepository->shouldReceive('find')->andReturn($objEntity);
        $mockRepository->shouldReceive('Find')->andReturn(true);

        /** @var FindInput */
        $mockInput = Mockery::mock(FindInput::class, [$id]);

        $ret = $uc->handle($mockInput);
        $this->assertInstanceOf(Output::class, $ret);
        $this->assertNotEmpty($ret->id);

        $mockRepository->shouldHaveReceived('find')->times(1);
    }

    private function mockRepository(): string|CustomerRepository|Mockery\MockInterface
    {
        return Mockery::mock(CustomerRepository::class);
    }
}
