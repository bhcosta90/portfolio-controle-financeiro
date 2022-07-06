<?php

namespace Tests\Unit\src\Application\Relationship\Modules\Company\Services;

use Core\Application\Relationship\Modules\Company\Repository\CompanyRepository;
use Core\Application\Relationship\Modules\Company\Services\UpdateService;
use Core\Application\Relationship\Modules\Company\Services\DTO\Update\{Input, Output};
use Core\Application\Relationship\Modules\Customer\Domain\CustomerEntity;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UpdateServiceTest extends TestCase
{
    public function testHandle()
    {
        $uc = new UpdateService(
            repository: $mockRepository = $this->mockRepository()
        );
        $objEntity = CustomerEntity::create(name:  'test', id: $id = Uuid::uuid4());

        $mockRepository->shouldReceive('find')->andReturn($objEntity);
        $mockRepository->shouldReceive('update')->andReturn(true);

        /** @var Input */
        $mockInput = Mockery::mock(Input::class, [$id, 'test']);

        $ret = $uc->handle($mockInput);
        $this->assertInstanceOf(Output::class, $ret);
        $this->assertNotEmpty($ret->id);

        $mockRepository->shouldHaveReceived('find')->times(1);
        $mockRepository->shouldHaveReceived('update')->times(1);
    }

    private function mockRepository(): string|CompanyRepository|Mockery\MockInterface
    {
        return Mockery::mock(CompanyRepository::class);
    }
}
