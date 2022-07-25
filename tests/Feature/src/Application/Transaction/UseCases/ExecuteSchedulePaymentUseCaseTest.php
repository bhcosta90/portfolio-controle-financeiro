<?php

namespace Tests\Feature\src\Application\Transaction\UseCases;

use App\Models\Account;
use App\Models\Tenant;
use App\Models\Transaction;
use Core\Application\Transaction\UseCases\ExecuteSchedulePaymentUseCase;
use Core\Application\Transaction\UseCases\DTO\ExecuteSchedulePayment\Input;
use DateTime;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ExecuteSchedulePaymentUseCaseTest extends TestCase
{
    use DatabaseMigrations;

    public function testExec()
    {
        $objAccount = Account::factory()->create([
            'tenant_id' => $this->tenant(),
            'entity_type' => 'test',
            'entity_id' => $idAccountEntity = str()->uuid(),
            'value' => 50,
        ]);

        $this->assertDatabaseHas('accounts', [
            'entity_id' => $idAccountEntity,
            'value' => 50,
        ]);

        $objTransaction = Transaction::factory()->create([
            'tenant_id' => $objAccount->tenant_id,
            'account_to_id' => $objAccount->id,
            'account_from_id' => $this->getIdAccountTenant($objAccount->tenant_id),
            'entity_type' => 'test',
            'entity_id' => str()->uuid(),
            'value' => 50,
            'date' => date('Y-m-d', strtotime('+1 day')),
            'type' => 1,
        ]);

        $uc = new ExecuteSchedulePaymentUseCase(
            $this->mockTransactionRepository(),
            $this->mockEventManagerInterface(),
        );

        $uc->handle(new Input((new DateTime())->modify('+1 day')));

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

    private function getIdAccountTenant($id): string
    {
        return Tenant::find($id)->idAccount();
    }
}
