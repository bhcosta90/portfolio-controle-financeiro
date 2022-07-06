<?php

namespace Tests\Unit\src\Application\Relationship\Modules\Company\Services;

use Core\Application\Relationship\Modules\Company\Repository\CompanyRepository;
use Core\Application\Relationship\Modules\Company\Services\CreateService;
use Core\Application\Relationship\Modules\Company\Services\DTO\Create\{Input, Output};
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CreateServiceTest extends TestCase
{
    public function testHandle()
    {
        $uc = new CreateService(
            repository: $mockRepository = $this->mockRepository()
        );
        $mockRepository->shouldReceive('insert')->andReturn(true);

        /** @var Input */
        $mockInput = Mockery::mock(Input::class, [Uuid::uuid4(), 'test']);

        $ret = $uc->handle($mockInput);
        $this->assertInstanceOf(Output::class, $ret);
        $this->assertNotEmpty($ret->id);

        $mockRepository->shouldHaveReceived('insert')->times(1);
    }

    private function mockRepository(): string|CompanyRepository|Mockery\MockInterface
    {
        return Mockery::mock(CompanyRepository::class);
    }
}
