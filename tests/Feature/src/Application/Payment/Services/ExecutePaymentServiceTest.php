<?php

namespace Tests\Feature\src\Application\Payment\Services;

use App\Models\AccountBank;
use App\Models\Payment;
use App\Models\Relationship;
use Core\Application\AccountBank\Repository\AccountBankRepository;
use Core\Application\Payment\Repository\PaymentRepository;
use Core\Application\Payment\Services\DTO\ExecutePayment\Input;
use Core\Application\Payment\Services\ExecutePaymentService;
use Core\Application\Relationship\Modules\Company\Repository\CompanyRepository;
use Core\Application\Relationship\Modules\Customer\Domain\CustomerEntity;
use Core\Application\Relationship\Modules\Customer\Repository\CustomerRepository;
use Core\Shared\Interfaces\TransactionInterface;
use DateTime;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ExecutePaymentServiceTest extends TestCase
{
    use DatabaseMigrations;

    public function testPaymentWithoutBank()
    {
        $objRelationship = Relationship::factory()->create(['entity' => CustomerEntity::class, 'value' => 0]);

        $basePayment = [
            'relationship_id' => $objRelationship->id,
            'relationship_type' => $objRelationship->entity,
            'value' => 50,
            'type' => 2,
        ];

        $data = Payment::factory(3)->create([
                'date' => (new DateTime())->modify('+10 minute')->format('Y-m-d H:i:s'),
            ] + $basePayment);

        $dataPayment = Payment::factory(3)->create([
                'date' => (new DateTime())->modify('-2 minute')->format('Y-m-d H:i:s'),
            ] + $basePayment);

        $service = $this->getService();
        $service->handle(new Input(new DateTime()));

        $this->assertDatabaseHas('payments', [
            'id' => $data[0]->id,
            'status' => 1,
            'value' => 50,
        ]);

        $this->assertDatabaseHas('payments', [
            'id' => $dataPayment[0]->id,
            'status' => 3,
            'value' => 50,
        ]);

        $this->assertDatabaseHas('relationships', [
            'id' => $objRelationship->id,
            'value' => 150,
        ]);

        $service = $this->getService();
        $service->handle(new Input((new DateTime())->modify('+1 hour')));

        $this->assertDatabaseHas('relationships', [
            'id' => $objRelationship->id,
            'value' => 300,
        ]);
    }

    private function getService()
    {
        return new ExecutePaymentService(
            repository: app(PaymentRepository::class),
            transaction: app(TransactionInterface::class),
            customer: app(CustomerRepository::class),
            company: app(CompanyRepository::class),
            account: app(AccountBankRepository::class),
        );
    }

    public function testPaymentBank()
    {
        $objRelationship = Relationship::factory()->create(['entity' => CustomerEntity::class, 'value' => 0]);
        $objAccount = AccountBank::factory()->create(['value' => 0]);

        $basePayment = [
            'relationship_id' => $objRelationship->id,
            'relationship_type' => $objRelationship->entity,
            'account_bank_id' => $objAccount->id,
            'value' => 50,
            'type' => 2,
        ];

        $data = Payment::factory(3)->create([
                'date' => (new DateTime())->modify('+10 minute')->format('Y-m-d H:i:s'),
            ] + $basePayment);

        $dataPayment = Payment::factory(3)->create([
                'date' => (new DateTime())->modify('-2 minute')->format('Y-m-d H:i:s'),
            ] + $basePayment);

        $service = $this->getService();
        $service->handle(new Input(new DateTime()));

        $this->assertDatabaseHas('payments', [
            'id' => $data[0]->id,
            'status' => 1,
            'value' => 50,
        ]);

        $this->assertDatabaseHas('payments', [
            'id' => $dataPayment[0]->id,
            'status' => 3,
            'value' => 50,
        ]);

        $this->assertDatabaseHas('relationships', [
            'id' => $objRelationship->id,
            'value' => 150,
        ]);

        $this->assertDatabaseHas('account_banks', [
            'id' => $objAccount->id,
            'value' => -150,
        ]);

        $service = $this->getService();
        $service->handle(new Input((new DateTime())->modify('+1 hour')));

        $this->assertDatabaseHas('relationships', [
            'id' => $objRelationship->id,
            'value' => 300,
        ]);

        $this->assertDatabaseHas('account_banks', [
            'id' => $objAccount->id,
            'value' => -300,
        ]);
    }

    public function testReceiveWithoutBank()
    {
        $objRelationship = Relationship::factory()->create(['entity' => CustomerEntity::class, 'value' => 0]);

        $basePayment = [
            'relationship_id' => $objRelationship->id,
            'relationship_type' => $objRelationship->entity,
            'value' => 50,
            'type' => 1,
        ];

        $data = Payment::factory(3)->create([
                'date' => date('Y-m-d H:i:s', strtotime('+10 minute')),
            ] + $basePayment);

        $dataPayment = Payment::factory(3)->create([
                'date' => date('Y-m-d H:i:s', strtotime('-10 seconds')),
            ] + $basePayment);

        $service = $this->getService();
        $service->handle(new Input(new DateTime()));

        $this->assertDatabaseHas('payments', [
            'id' => $data[0]->id,
            'status' => 1,
            'value' => 50,
        ]);

        $this->assertDatabaseHas('payments', [
            'id' => $dataPayment[0]->id,
            'status' => 3,
            'value' => 50,
        ]);

        $this->assertDatabaseHas('relationships', [
            'id' => $objRelationship->id,
            'value' => -150,
        ]);

        $service = $this->getService();
        $service->handle(new Input((new DateTime())->modify('+1 hour')));

        $this->assertDatabaseHas('relationships', [
            'id' => $objRelationship->id,
            'value' => -300,
        ]);
    }

    public function testReceiveBank()
    {
        $objRelationship = Relationship::factory()->create(['entity' => CustomerEntity::class, 'value' => 0]);
        $objAccount = AccountBank::factory()->create(['value' => 0]);

        $basePayment = [
            'relationship_id' => $objRelationship->id,
            'relationship_type' => $objRelationship->entity,
            'account_bank_id' => $objAccount->id,
            'value' => 50,
            'type' => 1,
        ];

        $data = Payment::factory(3)->create([
                'date' => (new DateTime())->modify('+10 minute')->format('Y-m-d H:i:s'),
            ] + $basePayment);

        $dataPayment = Payment::factory(3)->create([
                'date' => (new DateTime())->modify('-2 minute')->format('Y-m-d H:i:s'),
            ] + $basePayment);

        $service = $this->getService();
        $service->handle(new Input(new DateTime()));

        $this->assertDatabaseHas('payments', [
            'id' => $data[0]->id,
            'status' => 1,
            'value' => 50,
        ]);

        $this->assertDatabaseHas('payments', [
            'id' => $dataPayment[0]->id,
            'status' => 3,
            'value' => 50,
        ]);

        $this->assertDatabaseHas('relationships', [
            'id' => $objRelationship->id,
            'value' => -150,
        ]);

        $this->assertDatabaseHas('account_banks', [
            'id' => $objAccount->id,
            'value' => 150,
        ]);

        $service = $this->getService();
        $service->handle(new Input((new DateTime())->modify('+1 hour')));

        $this->assertDatabaseHas('relationships', [
            'id' => $objRelationship->id,
            'value' => -300,
        ]);

        $this->assertDatabaseHas('account_banks', [
            'id' => $objAccount->id,
            'value' => 300,
        ]);
    }
}
