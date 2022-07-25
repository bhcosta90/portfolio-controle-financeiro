<?php

namespace Tests\Feature\src\Application\Charge\Modules\Receive\UseCases;

use App\Models\Account;
use App\Models\Charge;
use App\Models\Relationship;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Core\Application\Charge\Modules\Receive\UseCases\PaymentUseCase;
use Core\Application\Charge\Modules\Receive\UseCases\DTO\Payment\{Input, Output};
use Tests\TestCase;

class PaymentUseCaseTest extends TestCase
{
    use DatabaseMigrations;

    protected PaymentUseCase $uc;
    protected $mockReceiveRepository;
    protected $mockCustomerRepository;
    protected $mockTenantRepository;
    protected $mockTransactionRepository;
    protected $mockEventManagerInterface;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uc = new PaymentUseCase(
            $this->mockReceiveRepository = $this->mockReceiveRepository(),
            $this->mockCustomerRepository = $this->mockCustomerRepository(),
            $this->mockTenantRepository = $this->mockTenantRepository(),
            $this->mockTransactionRepository = $this->mockTransactionRepository(),
            $this->mockEventManagerInterface = $this->mockEventManagerInterface(),
            $this->mockTransaction(),
            $this->mockBankRepository(),
        );
    }

    public function testHandle()
    {
        $idTenant = $this->tenant();
        
        Relationship::factory()->create([
            'id' => $idCustomer = $this->id(),
            'tenant_id' => $idTenant,
            'entity' => CustomerEntity::class,
        ])->each(fn($obj) => Account::factory()->create([
            'tenant_id' => $idTenant,
            'entity_type' => $obj->entity,
            'entity_id' => $obj->id,
            'value' => 100,
        ]));

        $objCharge = Charge::factory()->create([
            'tenant_id' => (string) $idTenant,
            'relationship_id' => (string) $idCustomer,
            'relationship_type' => CustomerEntity::class,
            'value_charge' => 50,
        ]);

        $ret = $this->uc->handle(new Input($objCharge->id, 50, date('Y-m-d'), null));

        $this->assertDatabaseHas('accounts', [
            'entity_id' => $idCustomer,
            'value' => 50,
        ]);

        $this->assertDatabaseHas('accounts', [
            'entity_id' => $idTenant,
            'value' => 50,
        ]);

        $this->assertInstanceOf(Output::class, $ret);
        $this->assertTrue($ret->success);
        $this->assertEmpty($ret->charge);
    }

    public function testHandleWithNewCharge()
    {
        $idTenant = $this->tenant();
        
        Relationship::factory()->create([
            'id' => $idCustomer = $this->id(),
            'tenant_id' => $idTenant,
            'entity' => CustomerEntity::class,
        ])->each(fn($obj) => Account::factory()->create([
            'tenant_id' => $idTenant,
            'entity_type' => $obj->entity,
            'entity_id' => $obj->id,
            'value' => 100,
        ]));

        $objCharge = Charge::factory()->create([
            'tenant_id' => (string) $idTenant,
            'relationship_id' => (string) $idCustomer,
            'relationship_type' => CustomerEntity::class,
            'value_charge' => 50,
        ]);

        $ret = $this->uc->handle(new Input(
            $objCharge->id,
            30,
            date('Y-m-d'),
            null,
            true,
            date('Y-m-d', strtotime('+10 days'))
        ));

        $this->assertDatabaseHas('accounts', [
            'entity_id' => $idCustomer,
            'value' => 70,
        ]);

        $this->assertDatabaseHas('accounts', [
            'entity_id' => $idTenant,
            'value' => 30,
        ]);

        $this->assertInstanceOf(Output::class, $ret);
        $this->assertTrue($ret->success);
        $this->assertNotEmpty($ret->charge);
    }
}
