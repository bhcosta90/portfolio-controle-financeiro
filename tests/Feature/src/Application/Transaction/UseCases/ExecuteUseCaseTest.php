<?php

namespace Tests\Feature\src\Application\Transaction\UseCases;

use App\Models\Account;
use App\Models\Tenant;
use App\Models\Transaction;
use Core\Application\BankAccount\Modules\Bank\Domain\BankEntity;
use Core\Application\Transaction\UseCases\ExecuteUseCase;
use Core\Application\Transaction\UseCases\DTO\Execute\Input;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ExecuteUseCaseTest extends TestCase
{
    use DatabaseMigrations;

    public function testCredit()
    {
        $objAccount = Account::factory()->create([
            'tenant_id' => $this->tenant(),
            'entity_type' => 'test',
            'entity_id' => $idAccountEntity = str()->uuid(),
            'value' => 50,
        ]);

        $objTransaction = Transaction::factory()->create([
            'tenant_id' => $objAccount->tenant_id,
            'account_to_id' => $objAccount->id,
            'account_from_id' => $this->getIdAccountTenant($objAccount->tenant_id),
            'entity_type' => 'test',
            'entity_id' => str()->uuid(),
            'value' => 50,
            'type' => 1,
        ]);

        $uc = new ExecuteUseCase(
            $this->mockTransactionRepository(),
            $this->mockTransaction(),
            $this->mockAccountRepository(),
        );

        $ret = $uc->handle(new Input($objAccount->tenant_id, $objTransaction->id));
        $this->assertTrue($ret->success);

        $this->assertDatabaseHas('accounts', [
            'entity_id' => $idAccountEntity,
            'value' => 100,
        ]);

        $this->assertDatabaseHas('transactions', [
            'id' => $objTransaction->id,
            'value' => 50,
            'type' => 1,
            'status' => 2,
        ]);

        $this->assertDatabaseHas('accounts', [
            'entity_id' => $objAccount->tenant_id,
            'value' => 0,
        ]);
    }

    public function testDebit()
    {
        $objAccount = Account::factory()->create([
            'tenant_id' => $this->tenant(),
            'entity_type' => 'test',
            'entity_id' => $idAccountEntity = str()->uuid(),
            'value' => 50,
        ]);

        $objTransaction = Transaction::factory()->create([
            'tenant_id' => $objAccount->tenant_id,
            'account_to_id' => $objAccount->id,
            'account_from_id' => $this->getIdAccountTenant($objAccount->tenant_id),
            'entity_type' => 'test',
            'entity_id' => str()->uuid(),
            'value' => 50,
            'type' => 2,
        ]);

        $uc = new ExecuteUseCase(
            $this->mockTransactionRepository(),
            $this->mockTransaction(),
            $this->mockAccountRepository(),
        );

        $ret = $uc->handle(new Input($objAccount->tenant_id, $objTransaction->id));
        $this->assertTrue($ret->success);

        $this->assertDatabaseHas('accounts', [
            'entity_id' => $idAccountEntity,
            'value' => 0,
        ]);

        $this->assertDatabaseHas('transactions', [
            'id' => $objTransaction->id,
            'value' => 50,
            'type' => 2,
            'status' => 2,
        ]);

        $this->assertDatabaseHas('accounts', [
            'entity_id' => $objAccount->tenant_id,
            'value' => 0,
        ]);
    }

    public function testCreditBank()
    {
        $objAccount = Account::factory()->create([
            'tenant_id' => $this->tenant(),
            'entity_type' => 'test',
            'entity_id' => $idAccountEntity = str()->uuid(),
            'value' => 50,
        ]);

        $objTransaction = Transaction::factory()->create([
            'tenant_id' => $objAccount->tenant_id,
            'account_to_id' => $objAccount->id,
            'account_from_id' => $this->getIdAccountTenant($objAccount->tenant_id),
            'entity_type' => BankEntity::class,
            'entity_id' => str()->uuid(),
            'value' => 50,
            'type' => 1,
        ]);

        $uc = new ExecuteUseCase(
            $this->mockTransactionRepository(),
            $this->mockTransaction(),
            $this->mockAccountRepository(),
        );

        $ret = $uc->handle(new Input($objAccount->tenant_id, $objTransaction->id));
        $this->assertTrue($ret->success);

        $this->assertDatabaseHas('accounts', [
            'entity_id' => $idAccountEntity,
            'value' => 100,
        ]);

        $this->assertDatabaseHas('transactions', [
            'id' => $objTransaction->id,
            'value' => 50,
            'type' => 1,
            'status' => 2,
        ]);

        $this->assertDatabaseHas('accounts', [
            'entity_id' => $objAccount->tenant_id,
            'value' => 0,
        ]);
    }

    public function testDebitBank()
    {
        $objAccount = Account::factory()->create([
            'tenant_id' => $this->tenant(),
            'entity_type' => 'test',
            'entity_id' => $idAccountEntity = str()->uuid(),
            'value' => 50,
        ]);

        $objTransaction = Transaction::factory()->create([
            'tenant_id' => $objAccount->tenant_id,
            'account_to_id' => $objAccount->id,
            'account_from_id' => $this->getIdAccountTenant($objAccount->tenant_id),
            'entity_type' => BankEntity::class,
            'entity_id' => str()->uuid(),
            'value' => 50,
            'type' => 2,
        ]);

        $uc = new ExecuteUseCase(
            $this->mockTransactionRepository(),
            $this->mockTransaction(),
            $this->mockAccountRepository(),
        );

        $ret = $uc->handle(new Input($objAccount->tenant_id, $objTransaction->id));
        $this->assertTrue($ret->success);

        $this->assertDatabaseHas('accounts', [
            'entity_id' => $idAccountEntity,
            'value' => 0,
        ]);

        $this->assertDatabaseHas('transactions', [
            'id' => $objTransaction->id,
            'value' => 50,
            'type' => 2,
            'status' => 2,
        ]);

        $this->assertDatabaseHas('accounts', [
            'entity_id' => $objAccount->tenant_id,
            'value' => 0,
        ]);
    }

    private function getIdAccountTenant($id): string
    {
        return Tenant::find($id)->idAccount();
    }
}
