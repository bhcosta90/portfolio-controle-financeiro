<?php

namespace Tests\Unit\src\Financial\Recurrence\UseCases;

use PHPUnit\Framework\TestCase;
use Core\Financial\Recurrence\Domain\RecurrenceEntity as Entity;
use Core\Financial\Recurrence\Repository\RecurrenceRepositoryInterface as Repo;
use Core\Financial\Recurrence\UseCases\DeleteUseCase;
use Core\Shared\UseCases\Delete\{DeleteInput, DeleteOutput};
use Mockery;
use Ramsey\Uuid\Uuid;

class DeleteUseCaseTest extends TestCase
{
    public function testHandleError()
    {
        $entity = Entity::create('bruno', 50, $id = Uuid::uuid4());

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
        $entity = Entity::create('bruno', 50, $id = Uuid::uuid4());

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
