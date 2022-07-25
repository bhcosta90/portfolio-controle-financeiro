<?php

namespace Tests\Unit\src\Application\Relationship\Modules\Customer\UseCases;

use Core\Application\Relationship\Modules\Customer\Domain\CustomerEntity;
use Tests\UnitCase as TestCase;
use Core\Application\Relationship\Modules\Customer\UseCases\FindUseCase;
use Core\Application\Relationship\Modules\Customer\UseCases\DTO\Find\Output;
use Core\Shared\UseCases\Find\FindInput;

class FindUseCaseTest extends TestCase
{
    public function testCreate()
    {
        $uc = new FindUseCase($mock = $this->mockCustomerRepository());
        $id = $this->id(); 

        $ret = $this->mock(fn() => $uc->handle(new FindInput($id)), [
            [
                'mock' => $mock,
                'action' => 'find',
                'return' => CustomerEntity::create($this->id(), 'test2', 0, null, $id),
            ]
        ]);
        $this->assertInstanceOf(Output::class, $ret);
    }
}
