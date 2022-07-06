<?php

namespace Tests\Unit\src\Application\Relationship\Modules\Customer\Services;

use Core\Application\Relationship\Modules\Customer\Repository\CustomerRepository;
use Core\Application\Relationship\Modules\Customer\Services\DeleteService;
use Core\Application\Relationship\Modules\Customer\Domain\CustomerEntity;
use Core\Shared\UseCases\Delete\{DeleteInput, DeleteOutput};
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class DeleteServiceTest extends TestCase
{
    public function testHandle()
    {
        $uc = new DeleteService(
            repository: $mockRepository = $this->mockRepository()
        );
        $objEntity = CustomerEntity::create(name: 'test', id: $id = Uuid::uuid4());

        $mockRepository->shouldReceive('find')->andReturn($objEntity);
        $mockRepository->shouldReceive('delete')->andReturn(true);

        /** @var DeleteInput */
        $mockInput = Mockery::mock(DeleteInput::class, [$id]);

        $ret = $uc->handle($mockInput);
        $this->assertInstanceOf(DeleteOutput::class, $ret);
        $this->assertTrue($ret->success);

        $mockRepository->shouldHaveReceived('find')->times(1);
        $mockRepository->shouldHaveReceived('delete')->times(1);
    }

    private function mockRepository(): string|CustomerRepository|Mockery\MockInterface
    {
        return Mockery::mock(CustomerRepository::class);
    }
}
