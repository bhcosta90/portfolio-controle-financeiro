<?php

namespace Tests\Unit\Costa\Modules\Relationship\UseCases\Customer;

use Costa\Modules\Relationship\UseCases\Customer\CustomerCreateUseCase as UseCase;
use Costa\Modules\Relationship\UseCases\Customer\DTO\Create\Input;
use Costa\Modules\Relationship\UseCases\Customer\DTO\Create\Output;
use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Costa\Modules\Account\Entities\MockAccountEntityTrait;
use Tests\Unit\Costa\Modules\Account\Repository\MockAccountRepositoryInterfaceTrait;
use Tests\Unit\Costa\Modules\Relationship\Entities\MockCustomerEntityTrait;
use Tests\Unit\Costa\Modules\Relationship\Repository\MockCustomerRepositoryInterfaceTrait;
use Tests\Unit\Costa\Shareds\Contracts\MockTransactionContractTrait;

class CustomerCreateUseCaseTest extends TestCase
{
    use MockCustomerRepositoryInterfaceTrait, MockTransactionContractTrait, MockCustomerEntityTrait;
    use MockAccountRepositoryInterfaceTrait, MockAccountEntityTrait;

    public function testExec()
    {
        $repo = $this->mockCustomerRepositoryInterface();
        $repo->shouldReceive('insert')->andReturn($this->mockCustomerEntity());

        $account = $this->mockAccountRepositoryInterface();
        $account->shouldReceive('insert')->andReturn($this->mockAccountEntity());

        $transaction = $this->mockTransactionContract();

        $uc = new UseCase(
            repo: $repo,
            transaction: $transaction,
            account: $account,
        );

        /** @var Input */
        $mockInput = Mockery::mock(Input::class, [
            'bruno costa'
        ]);
        $resp = $uc->exec($mockInput);

        $this->assertInstanceOf(Output::class, $resp);
        $this->assertEquals('bruno costa', $resp->name);
        $this->assertNotEmpty($resp->id);        

        $repo->shouldHaveReceived('insert')->times(limit: 1);
        $transaction->shouldHaveReceived('commit')->times(limit: 1);
    }
}
