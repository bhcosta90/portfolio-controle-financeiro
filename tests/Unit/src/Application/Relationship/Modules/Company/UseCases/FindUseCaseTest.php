<?php

namespace Tests\Unit\src\Application\Relationship\Modules\Company\UseCases;

use Core\Application\Relationship\Modules\Company\Domain\CompanyEntity;
use Tests\UnitCase as TestCase;
use Core\Application\Relationship\Modules\Company\UseCases\FindUseCase;
use Core\Application\Relationship\Modules\Company\UseCases\DTO\Find\Output;
use Core\Shared\UseCases\Find\FindInput;

class FindUseCaseTest extends TestCase
{
    public function testCreate()
    {
        $uc = new FindUseCase($mock = $this->mockCompanyRepository());
        $id = $this->id(); 

        $ret = $this->mock(fn() => $uc->handle(new FindInput($id)), [
            [
                'mock' => $mock,
                'action' => 'find',
                'return' => CompanyEntity::create($this->id(), 'test2', 0, null, $id),
            ]
        ]);
        $this->assertInstanceOf(Output::class, $ret);
    }
}
