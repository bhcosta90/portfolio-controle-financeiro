<?php

namespace Tests\Feature\src\Application\Charge\Modules\Payment\UseCases;

use App\Models\Account;
use App\Models\Charge;
use App\Models\Relationship;
use Core\Application\Charge\Modules\Payment\UseCases\PaymentUseCase;
use Core\Application\Charge\Modules\Payment\UseCases\DTO\Payment\{Input, Output};
use Core\Application\Relationship\Modules\Customer\Domain\CustomerEntity;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PaymentUseCaseTest extends TestCase
{
    use DatabaseMigrations;

    protected PaymentUseCase $uc;
    protected $mockPaymentRepository;
    protected $mockCompanyRepository;
    protected $mockTenantRepository;
    protected $mockTransactionRepository;
    protected $mockEventManagerInterface;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uc = new PaymentUseCase(
            $this->mockPaymentRepository = $this->mockPaymentRepository(),
            $this->mockCompanyRepository = $this->mockCompanyRepository(),
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
            'value' => 150,
        ]);

        $this->assertDatabaseHas('accounts', [
            'entity_id' => $idTenant,
            'value' => -50,
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
            'value' => 130,
        ]);

        $this->assertDatabaseHas('accounts', [
            'entity_id' => $idTenant,
            'value' => -30,
        ]);

        $this->assertInstanceOf(Output::class, $ret);
        $this->assertTrue($ret->success);
        $this->assertNotEmpty($ret->charge);
    }
}
