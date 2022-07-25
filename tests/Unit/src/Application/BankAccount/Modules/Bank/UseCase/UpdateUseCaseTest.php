<?php

namespace Tests\Unit\src\Application\BankAccount\Modules\Bank\UseCase;

use App\Models\Tenant;
use Core\Application\BankAccount\Modules\Bank\Domain\BankEntity;
use Core\Application\BankAccount\Modules\Bank\UseCases\UpdateUseCase as UseCase;
use Core\Application\BankAccount\Modules\Bank\UseCases\DTO\Update\{Input, Output};
use Core\Application\Tenant\Domain\TenantEntity;
use Mockery;
use Ramsey\Uuid\Uuid;
use Tests\UnitCase as TestCase;

class UpdateUseCaseTest extends TestCase
{
    public function testCreate()
    {
        $uc = new UseCase(
            $this->mockTransaction(),
            $mockBankRepository = $this->mockBankRepository(),
            $mockTransactionRepository = $this->mockTransactionRepository(),
            $mockTenantRepository = $this->mockTenantRepository(),
            $mockAccountRepository = $this->mockAccountRepository(),
        );

        $ret = $this->mock(fn() => $uc->handle($this->mockInput()), [
            [
                'mock' => $mockBankRepository,
                'action' => 'find',
                'return' => BankEntity::create($this->id(), 'test', 50, true),
                'times' => 1
            ],
            [
                'mock' => $mockTenantRepository,
                'action' => 'find',
                'return' => TenantEntity::create(0, $this->id(), $this->id()),
                'times' => 1
            ],
            [
                'mock' => $mockBankRepository,
                'action' => 'update',
                'return' => true,
                'times' => 1
            ],
            [
                'mock' => $mockTransactionRepository,
                'action' => 'insert',
                'return' => true,
                'times' => 1
            ],
            [
                'mock' => $mockAccountRepository,
                'action' => 'subValue',
                'return' => true
            ],
        ]);
        $this->assertInstanceOf(Output::class, $ret);
    }

    /** @return Input|Mockery\MockInterface */
    protected function mockInput(
        ?string $id = null,
        ?string $name = null,
        ?float $value = 0,
        ?bool $active = true,
        ?string $bankCode = null,
        ?string $agency = null,
        ?string $agencyDigit = null,
        ?string $account = null,
        ?string $accountDigit = null,
        ?string $accountEntity = null,
    ) {
        return Mockery::mock(Input::class, [
            $id ?: Uuid::uuid4(),
            $name ?: 'test',
            $value ?: 0,
            $active,
            $bankCode,
            $agency,
            $agencyDigit,
            $account,
            $accountDigit,
            $accountEntity,
        ]);
    }
}
