<?php

namespace Tests\Unit\src\Application\Relationship\Modules\Customer\UseCases;

use Core\Application\Relationship\Modules\Customer\Domain\CustomerEntity;
use Core\Application\Relationship\Modules\Customer\UseCases\UpdateUseCase;
use Core\Application\Relationship\Modules\Customer\UseCases\DTO\Update\{Input, Output};
use Tests\UnitCase as TestCase;

class UpdateUseCaseTest extends TestCase
{
    public function testCreate()
    {
        $uc = new UpdateUseCase($mock = $this->mockCustomerRepository());
        $id = $this->id(); 
        $tenant = $this->id();

        $ret = $this->mock(fn() => $uc->handle(new Input($id, $tenant, 'test')), [
            [
                'mock' => $mock,
                'action' => 'find',
                'return' => CustomerEntity::create($tenant, 'test2', 0, null, $id),
            ],
            [
                'mock' => $mock,
                'action' => 'update',
                'return' => true,
            ]
        ]);
        $this->assertInstanceOf(Output::class, $ret);
    }
}
