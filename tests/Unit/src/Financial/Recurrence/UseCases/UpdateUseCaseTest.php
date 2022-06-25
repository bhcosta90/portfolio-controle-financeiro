<?php

namespace Tests\Unit\src\Financial\Recurrence\UseCases;

use Core\Financial\Recurrence\Domain\RecurrenceEntity as Entity;
use Core\Financial\Recurrence\Repository\RecurrenceRepositoryInterface as Repo;
use Core\Financial\Recurrence\UseCases\UpdateUseCase;
use Core\Financial\Recurrence\UseCases\DTO\Update\{UpdateInput, UpdateOutput};
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class UpdateUseCaseTest extends TestCase
{
    public function testHandle()
    {
        $entity = Entity::create('bruno', 50, $id = Uuid::uuid4());

        /** @var Repo|Mockery\MockInterface */
        $mock = Mockery::mock(stdClass::class, Repo::class);
        $mock->shouldReceive('find')->andReturn($entity);
        $mock->shouldReceive('update')->andReturn(true);
        
        /** @var UpdateInput|Mockery\MockInterface */
        $mockInput = $this->getMockClass(originalClassName: UpdateInput::class);

        $uc = new UpdateUseCase(
            repo: $mock
        );

        $handle = $uc->handle(new $mockInput($id, 'bruno costa', 50));
        $mock->shouldHaveReceived('find')->times(1);
        $mock->shouldHaveReceived('update')->times(1);
        $this->assertInstanceOf(UpdateOutput::class, $handle);
    }
}
