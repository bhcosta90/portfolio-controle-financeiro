<?php

namespace Tests\Unit\Costa\Modules\Relationship\UseCases\Supplier;

use Costa\Modules\Relationship\UseCases\Supplier\SupplierCreateUseCase as UseCase;
use Costa\Modules\Relationship\UseCases\Supplier\DTO\Create\Input;
use Costa\Modules\Relationship\UseCases\Supplier\DTO\Create\Output;
use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Costa\Modules\Account\Entities\MockAccountEntityTrait;
use Tests\Unit\Costa\Modules\Account\Repository\MockAccountRepositoryInterfaceTrait;
use Tests\Unit\Costa\Modules\Relationship\Entities\MockSupplierEntityTrait;
use Tests\Unit\Costa\Modules\Relationship\Repository\MockSupplierRepositoryInterfaceTrait;
use Tests\Unit\Costa\Shareds\Contracts\MockTransactionContractTrait;

class SupplierCreateUseCaseTest extends TestCase
{
    use MockSupplierRepositoryInterfaceTrait, MockTransactionContractTrait, MockSupplierEntityTrait;
    use MockAccountRepositoryInterfaceTrait, MockAccountEntityTrait;
    
    public function testExec()
    {
        $repo = $this->mockSupplierRepositoryInterface();
        $repo->shouldReceive('insert')->andReturn($this->mockSupplierEntity());

        $transaction = $this->mockTransactionContract();

        $account = $this->mockAccountRepositoryInterface();
        $account->shouldReceive('insert')->andReturn($this->mockAccountEntity());

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
