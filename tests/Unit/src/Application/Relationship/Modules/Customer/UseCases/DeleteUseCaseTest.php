<?php

namespace Tests\Unit\src\Application\Relationship\Modules\Customer\UseCases;

use Core\Application\Relationship\Modules\Customer\Domain\CustomerEntity;
use Core\Application\Relationship\Modules\Customer\UseCases\DeleteUseCase;
use Core\Shared\UseCases\Delete\{DeleteInput, DeleteOutput};
use Tests\UnitCase as TestCase;

class DeleteUseCaseTest extends TestCase
{
    public function testCreate()
    {
        $uc = new DeleteUseCase($mock = $this->mockCustomerRepository());
        $id = $this->id(); 

        $ret = $this->mock(fn() => $uc->handle(new DeleteInput($id)), [
            [
                'mock' => $mock,
                'action' => 'find',
                'return' => CustomerEntity::create($this->id(), 'test2', 0, null, $id),
            ],
            [
                'mock' => $mock,
                'action' => 'delete',
                'return' => true,
            ]
        ]);
        $this->assertInstanceOf(DeleteOutput::class, $ret);
    }
}
