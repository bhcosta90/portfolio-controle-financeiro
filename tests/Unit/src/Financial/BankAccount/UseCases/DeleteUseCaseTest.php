<?php

namespace Tests\Unit\src\Financial\BankAccount\UseCases;

use PHPUnit\Framework\TestCase;
use Core\Financial\BankAccount\Domain\BankAccountEntity as Entity;
use Core\Financial\BankAccount\Repository\BankAccountRepositoryInterface as Repo;
use Core\Financial\BankAccount\UseCases\DeleteUseCase;
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
