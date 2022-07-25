<?php

namespace Tests\Unit\src\Application\BankAccount\Modules\Bank\UseCase;

use Core\Application\BankAccount\Modules\Bank\Domain\BankEntity;
use Core\Application\BankAccount\Modules\Bank\UseCases\FindUseCase;
use Core\Shared\UseCases\Find\FindInput;
use Core\Application\BankAccount\Modules\Bank\UseCases\DTO\Find\Output;
use Mockery;
use Tests\UnitCase as TestCase;
use Ramsey\Uuid\Uuid;

class FindUseCaseTest extends TestCase
{
    public function testHandle()
    {
        $uc = new FindUseCase(
            repository: $mockRepository = $this->mockBankRepository()
        );
        
        $objEntity = BankEntity::create(
            tenant: Uuid::uuid4(),
            name: 'test',
            value: 0,
            id: $id = Uuid::uuid4(),
            active: true
        );


        /** @var FindInput */
        $mockInput = Mockery::mock(FindInput::class, [$id]);

        $ret = $this->mock(fn() => $uc->handle($mockInput), [
            [
                'mock' => $mockRepository,
                'action' => 'find',
                'return' => $objEntity,
            ]
        ]);
        $this->assertInstanceOf(Output::class, $ret);
        $this->assertNotEmpty($ret->id);
    }
}
