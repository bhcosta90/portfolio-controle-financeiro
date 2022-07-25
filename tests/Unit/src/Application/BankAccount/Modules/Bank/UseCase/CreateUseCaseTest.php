<?php

namespace Tests\Unit\src\Application\BankAccount\Modules\Bank\UseCase;

use Core\Application\BankAccount\Modules\Bank\UseCases\CreateUseCase as UseCase;
use Core\Application\BankAccount\Modules\Bank\UseCases\DTO\Create\{Input, Output};
use Mockery;
use Tests\UnitCase as TestCase;

class CreateUseCaseTest extends TestCase
{
    public function testCreate()
    {
        $uc = new UseCase(
            $this->mockTransaction(),
            $mockBankRepository = $this->mockBankRepository(),
            $mockAccountRepository = $this->mockAccountRepository()
        );

        $ret = $this->mock(fn () => $uc->handle($this->mockInput()), [
            [
                'mock' => $mockBankRepository,
                'action' => 'insert',
                'return' => true,
                'times' => 1
            ],
            [
                'mock' => $mockAccountRepository,
                'action' => 'insert',
                'return' => true,
                'times' => 1
            ]
        ]);

        $this->assertInstanceOf(Output::class, $ret);
    }

    /** @return Input|Mockery\MockInterface */
    protected function mockInput(
        ?string $tenant = null,
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
            $tenant ?: $this->id(),
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
