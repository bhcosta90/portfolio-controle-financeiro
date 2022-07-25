<?php

namespace Tests\Unit\src\Application\Relationship\Modules\Company\UseCases;

use Core\Application\Relationship\Modules\Company\UseCases\CreateUseCase;
use Core\Application\Relationship\Modules\Company\UseCases\DTO\Create\{Input, Output};
use Tests\UnitCase as TestCase;

class CreateUseCaseTest extends TestCase
{
    public function testCreate()
    {
        $uc = new CreateUseCase(
            $mock = $this->mockCompanyRepository(),
            $mockAccountRepository = $this->mockAccountRepository(),
            $this->mockTransaction(),
        );
        $ret = $this->mock(fn() => $uc->handle(new Input($this->id(), 'test')), [
            [
                'mock' => $mock,
                'action' => 'insert',
                'return' => true,
            ],
            [
                'mock' => $mockAccountRepository,
                'action' => 'insert',
                'return' => true,
            ]
        ]);
        $this->assertInstanceOf(Output::class, $ret);
    }
}
