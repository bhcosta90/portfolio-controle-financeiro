<?php

namespace Tests\Unit\src\Financial\Charge\Modules\Receive\UseCases;

use PHPUnit\Framework\TestCase;
USE Core\Financial\Charge\Modules\Receive\Domain\ReceiveEntity as Entity;
use Core\Financial\Charge\Shared\Enums\ChargeTypeEnum;
use Core\Financial\Charge\Modules\Receive\UseCases\DeleteUseCase;
use Core\Financial\Charge\Modules\Receive\Repository\ReceiveRepositoryInterface as Repo;
use Core\Financial\Relationship\Modules\Customer\Domain\CustomerEntity;
use Core\Shared\UseCases\Delete\{DeleteInput, DeleteOutput};
use Mockery;
use Ramsey\Uuid\Uuid;

class DeleteUseCaseTest extends TestCase
{
    public function testHandleError()
    {
        $group = Uuid::uuid4();
        $id = Uuid::uuid4();

        $entity = Entity::create(
            $group,
            50,
            CustomerEntity::create('teste', null, null),
            ChargeTypeEnum::CREDIT->value,
            '2022-01-01',
            null,
            1,
            $id,
        );

        /** @var Repo|Mockery\MockInterface */
        $mock = Mockery::mock(stdClass::class, Repo::class);
        $mock->shouldReceive('find')->andReturn($entity);
        $mock->shouldReceive('delete')->andReturn(false);
        
        /** @var UpdateInput|Mockery\MockInterface */
        $mockInput = $this->getMockClass(originalClassName: DeleteInput::class);

        $uc = new DeleteUseCase(
            repo: $mock
        );

        $handle = $uc->handle(new $mockInput($id));
        $mock->shouldHaveReceived('find')->times(1);
        $mock->shouldHaveReceived('delete')->times(1);
        $this->assertInstanceOf(DeleteOutput::class, $handle);
        $this->assertFalse($handle->success);
    }

    public function testHandle()
    {
        $group = Uuid::uuid4();
        $id = Uuid::uuid4();

        $entity = Entity::create(
            $group,
            50,
            CustomerEntity::create('teste', null, null),
            ChargeTypeEnum::CREDIT->value,
            '2022-01-01',
            null,
            1,
            $id,
        );

        /** @var Repo|Mockery\MockInterface */
        $mock = Mockery::mock(stdClass::class, Repo::class);
        $mock->shouldReceive('find')->andReturn($entity);
        $mock->shouldReceive('delete')->andReturn(true);
        
        /** @var UpdateInput|Mockery\MockInterface */
        $mockInput = $this->getMockClass(originalClassName: DeleteInput::class);

        $uc = new DeleteUseCase(
            repo: $mock
        );

        $handle = $uc->handle(new $mockInput($id));
        $mock->shouldHaveReceived('find')->times(1);
        $mock->shouldHaveReceived('delete')->times(1);
        $this->assertInstanceOf(DeleteOutput::class, $handle);
        $this->assertTrue($handle->success);
    }
}
