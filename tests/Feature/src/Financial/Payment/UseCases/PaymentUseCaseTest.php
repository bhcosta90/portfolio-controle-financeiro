<?php

namespace Tests\Feature\src\Financial\Payment\UseCases;

use App\Models\Account;
use App\Models\Charge;
use App\Models\Payment;
use Core\Financial\Account\Repository\AccountRepositoryInterface;
use Core\Financial\Payment\Repository\PaymentRepositoryInterface;
use Core\Financial\Payment\UseCases\PaymentUseCase;
use Core\Financial\Payment\UseCases\DTO\Payment\PaymentInput;
use Core\Shared\Interfaces\TransactionInterface;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PaymentUseCaseTest extends TestCase
{
    use DatabaseMigrations;

    public function testHandle()
    {
        $charge = Charge::factory()->create(['value_charge' => 100]);

        $accountFrom = Account::create([
            'id' => str()->uuid(),
            'entity_id' => $charge->relationship_id,
            'entity_type' => $charge->relationship_type,
            'value' => 0,
        ]);

        $accountTo = Account::create([
            'id' => str()->uuid(),
            'entity_id' => $charge->relationship_id,
            'entity_type' => $charge->relationship_type,
            'value' => 0,
        ]);

        $payment = Payment::factory()->create([
            'entity_id' => $charge->id, 
            'entity_type' => $charge->entity,
            'account_from_id' => $accountFrom,
            'account_to_id' => $accountTo,
            'value' => 100,
        ]);

        $uc = new PaymentUseCase(
            payment: app(PaymentRepositoryInterface::class),
            account: app(AccountRepositoryInterface::class),
            transaction: app(TransactionInterface::class),
        );

        $uc->handle(new PaymentInput(
            id: $payment->id,
            value: 100,
            accountFromId: $accountFrom->id,
            accountToId: $accountTo->id,
        ));

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 2,
        ]);

        $this->assertDatabaseHas('accounts', [
            'id' => $accountFrom->id,
            'value' => -100,
        ]);

        $this->assertDatabaseHas('accounts', [
            'id' => $accountTo->id,
            'value' => 100,
        ]);
    }
}
