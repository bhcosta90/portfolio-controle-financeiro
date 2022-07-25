<?php

namespace Tests\Feature\src\Application\BankAccount\Modules\Bank\UseCase;

use Core\Application\BankAccount\Modules\Bank\Domain\Exceptions\BankException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Core\Application\BankAccount\Modules\Bank\UseCases\UpdateUseCase as UseCase;
use Core\Application\BankAccount\Modules\Bank\UseCases\DTO\Update\{Input, Output};
use Tests\TestCase;

class UpdateUseCaseTest extends TestCase
{
    use DatabaseMigrations;

    public function testExec()
    {
        $uc = new UseCase(
            $this->mockTransaction(),
            $this->mockBankRepository(),
            $this->mockTransactionRepository(),
            $this->mockTenantRepository(),
            $this->mockAccountRepository(),
        );

        $objBank = $this->bank(params: [
            'tenant_id' => $this->tenant(),
            'value' => 50
        ]);
        $ret = $uc->handle(new Input(
            $objBank->id,
            'test',
            50,
            true
        ));

        $this->assertDatabaseHas('banks', [
            'id' => $ret->id,
            'name' => "test",
        ]);

        $this->assertDatabaseHas('accounts', [
            'entity_id' => $ret->id,
            'value' => 50,
        ]);
    }

    public function testExecUpdateValue()
    {
        $uc = new UseCase(
            $this->mockTransaction(),
            $this->mockBankRepository(),
            $this->mockTransactionRepository(),
            $this->mockTenantRepository(),
            $this->mockAccountRepository(),
        );

        $objBank = $this->bank(params: [
            'tenant_id' => $this->tenant(),
            'value' => 50
        ]);

        $ret = $uc->handle(new Input(
            $objBank->id,
            'test',
            100,
            true
        ));

        $this->assertDatabaseHas('banks', [
            'id' => $ret->id,
            'name' => "test",
        ]);

        $this->assertDatabaseHas('accounts', [
            'entity_id' => $ret->id,
            'value' => 100,
        ]);

        $this->assertNotEmpty($ret->transaction);
        $this->assertDatabaseHas('transactions', [
            'id' => $ret->transaction,
            'value' => 50,
            'type' => 1,
            'status' => 2,
        ]);

        $ret = $uc->handle(new Input(
            $objBank->id,
            'test',
            20,
            true
        ));

        $this->assertDatabaseHas('accounts', [
            'entity_id' => $ret->id,
            'value' => 20,
        ]);

        $this->assertDatabaseHas('transactions', [
            'id' => $ret->transaction,
            'value' => 80,
            'type' => 2,
            'status' => 2,
        ]);
    }

    public function testUpdateBank(){
        $objBank = $this->bank(params: [
            'tenant_id' => $this->tenant(),
            'value' => 50
        ]);

        $uc = new UseCase(
            $this->mockTransaction(),
            $this->mockBankRepository(),
            $this->mockTransactionRepository(),
            $this->mockTenantRepository(),
            $this->mockAccountRepository(),
        );
        
        $ret = $uc->handle(new Input(
            $objBank->id,
            'test',
            100,
            true,
            "1", 
            "2", 
            "3", 
            "4", 
            "5"
        ));

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

    public function testExceptionUpdateBank(){
        $this->expectException(BankException::class);
        $this->expectExceptionMessage('Bank details cannot be changed, please create a new bank account');

        $objBank = $this->bank(params: [
            'tenant_id' => $this->tenant(),
            'value' => 50,
            'code' => "1",
            'agency' => "2",
            'agency_digit' => "3",
            'account' => "4",
            'account_digit' => "5",
        ]);

        $uc = new UseCase(
            $this->mockTransaction(),
            $this->mockBankRepository(),
            $this->mockTransactionRepository(),
            $this->mockTenantRepository(),
            $this->mockAccountRepository(),
        );

        $uc->handle(new Input(
            $objBank->id,
            'test',
            100, 
            "1", 
            "2", 
            "3", 
            "4", 
            "5"
        ));
    }
}
