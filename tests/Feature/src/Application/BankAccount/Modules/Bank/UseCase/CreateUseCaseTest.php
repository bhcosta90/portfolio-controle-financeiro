<?php

namespace Tests\Feature\src\Application\BankAccount\Modules\Bank\UseCase;

use Core\Application\BankAccount\Modules\Bank\UseCases\CreateUseCase as UseCase;
use Core\Application\BankAccount\Modules\Bank\UseCases\DTO\Create\{Input, Output};

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CreateUseCaseTest extends TestCase
{
    use DatabaseMigrations;

    public function testExec()
    {
        $uc = new UseCase(
            $this->mockTransaction(),
            $this->mockBankRepository(),
            $this->mockAccountRepository()
        );

        $ret = $uc->handle(new Input($this->tenant(), "test", 50, "1", "2", "3", "4", "5"));

        $this->assertDatabaseHas('accounts', [
            'entity_type' => \Core\Application\BankAccount\Modules\Bank\Domain\BankEntity::class,
            'entity_id' => $ret->id,
            'value' => 50,
        ]);

        $this->assertDatabaseHas('banks', [
            'id' => $ret->id,
            'name' => "test",
            'code' => "1",
            'agency' => "2",
            'agency_digit' => "3",
            'account' => "4",
            'account_digit' => "5",
        ]);
    }
}
