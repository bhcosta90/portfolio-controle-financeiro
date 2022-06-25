<?php

namespace Tests\Unit\src\Financial\Recurrence\UseCases;

use Core\Financial\Recurrence\Repository\RecurrenceRepositoryInterface as Repo;
use Core\Financial\Recurrence\UseCases\CreateUseCase;
use Core\Financial\Recurrence\UseCases\DTO\Create\{CreateInput, CreateOutput};
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

class CreateUseCaseTest extends TestCase
{
    public function testHandle()
    {
        /** @var Repo|Mockery\MockInterface */
        $mock = Mockery::mock(stdClass::class, Repo::class);
        $mock->shouldReceive('insert')->andReturn(true);
        
        /** @var CreateInput|Mockery\MockInterface */
        $mockInput = $this->getMockClass(originalClassName: CreateInput::class);

        $uc = new CreateUseCase(
            repo: $mock
        );

        $handle = $uc->handle(new $mockInput('bruno costa', 50));
        $mock->shouldHaveReceived('insert')->times(1);
        $this->assertInstanceOf(CreateOutput::class, $handle);
    }
}
