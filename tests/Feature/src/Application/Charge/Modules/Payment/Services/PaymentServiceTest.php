<?php

namespace Tests\Feature\src\Application\Charge\Modules\Payment\Services;

use App\Models\AccountBank;
use App\Models\Charge;
use App\Models\Recurrence;
use Core\Application\AccountBank\Repository\AccountBankRepository;
use Core\Application\Charge\Modules\Payment\Repository\ChargePaymentRepository;
use Core\Application\Charge\Modules\Payment\Services\PaymentService;
use Core\Application\Charge\Modules\Payment\Services\DTO\Payment\{Input, Output};
use Core\Application\Charge\Modules\Recurrence\Repository\RecurrenceRepository;
use Core\Application\Payment\Repository\PaymentRepository;
use Core\Application\Relationship\Modules\Company\Repository\CompanyRepository;
use Core\Shared\Interfaces\TransactionInterface;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PaymentServiceTest extends TestCase
{
    use DatabaseMigrations;
    
    public function testHandle()
    {
        $uc = new PaymentService(
            repository: app(ChargePaymentRepository::class),
            transaction: app(TransactionInterface::class),
            relationship: app(CompanyRepository::class),
            recurrence: app(RecurrenceRepository::class),
            bank: app(AccountBankRepository::class),
            payment: app(PaymentRepository::class),
        );

        $objCharge = Charge::factory()->create(['value_charge' => 500]);
        $objBank = AccountBank::factory()->create();
        
        $ret = $uc->handle(new Input($objCharge->id, 50, $objBank->id, 50));
        $this->assertInstanceOf(Output::class, $ret);

        $this->assertDatabaseHas('charges', [
            'id' => $objCharge->id,
            'status' => 3,
            'value_charge' => 500,
            'value_pay' => 50,
        ]);

        $this->assertDatabaseHas('payments', [
            'charge_id' => $objCharge->id,
            'value' => 50,
            'type' => 2,
            'status' => 1,
        ]);
    }
    
    public function testHandleComplete()
    {
        $uc = new PaymentService(
            repository: app(ChargePaymentRepository::class),
            transaction: app(TransactionInterface::class),
            relationship: app(CompanyRepository::class),
            recurrence: app(RecurrenceRepository::class),
            bank: app(AccountBankRepository::class),
            payment: app(PaymentRepository::class),
        );

        $objCharge = Charge::factory()->create(['value_charge' => 500]);
        $objBank = AccountBank::factory()->create();
        
        $uc->handle(new Input($objCharge->id, 500, $objBank->id, 500));

        $this->assertDatabaseHas('charges', [
            'id' => $objCharge->id,
            'status' => 3,
        ]);
    }
    
    public function testHandleRecurrence()
    {
        $uc = new PaymentService(
            repository: app(ChargePaymentRepository::class),
            transaction: app(TransactionInterface::class),
            relationship: app(CompanyRepository::class),
            recurrence: app(RecurrenceRepository::class),
            bank: app(AccountBankRepository::class),
            payment: app(PaymentRepository::class),
        );

        $objRecurrence = Recurrence::factory()->create(['days' => 30]);
        $objCharge = Charge::factory()->create([
            'value_charge' => 500, 
            'recurrence_id' => $objRecurrence->id,
            'date' => '2022-01-01'
        ]);
        $objBank = AccountBank::factory()->create();
        
        $ret = $uc->handle(new Input($objCharge->id, 500, $objBank->id, 500));
        $this->assertDatabaseHas('charges', [
            'id' => $ret->idCharge,
            'status' => 1,
            'date' => '2022-02-01'
        ]);

        $ret = $uc->handle(new Input($idCharge = $ret->idCharge, 500, $objBank->id, 500));
        $this->assertDatabaseHas('charges', [
            'id' => $idCharge,
            'status' => 3,
        ]);

        $this->assertDatabaseHas('charges', [
            'id' => $ret->idCharge,
            'status' => 1,
            'date' => '2022-03-01'
        ]);
    }
}
