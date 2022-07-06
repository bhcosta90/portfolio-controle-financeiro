<?php

namespace Tests\Unit\src\Application\AccountBank\Services;

use Core\Application\AccountBank\Repository\AccountBankRepository;
use Core\Application\AccountBank\Services\CreateService;
use Core\Application\AccountBank\Services\DTO\Create\{Input, Output};
use Mockery;
use PHPUnit\Framework\TestCase;

class CreateServiceTest extends TestCase
{
    public function testHandle()
    {
        $uc = new CreateService(
            repository: $mockRepository = $this->mockRepository()
        );
        $mockRepository->shouldReceive('insert')->andReturn(true);

        /** @var Input */
        $mockInput = Mockery::mock(Input::class, ['test', 0]);

        $ret = $uc->handle($mockInput);
        $this->assertInstanceOf(Output::class, $ret);
        $this->assertNotEmpty($ret->id);

        $mockRepository->shouldHaveReceived('insert')->times(1);
    }

    private function mockRepository(): string|AccountBankRepository|Mockery\MockInterface
    {
        return Mockery::mock(AccountBankRepository::class);
    }
}
