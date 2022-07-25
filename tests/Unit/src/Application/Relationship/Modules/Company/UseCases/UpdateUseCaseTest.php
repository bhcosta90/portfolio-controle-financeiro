<?php

namespace Tests\Unit\src\Application\Relationship\Modules\Company\UseCases;

use Core\Application\Relationship\Modules\Company\Domain\CompanyEntity;
use Core\Application\Relationship\Modules\Company\UseCases\UpdateUseCase;
use Core\Application\Relationship\Modules\Company\UseCases\DTO\Update\{Input, Output};
use Tests\UnitCase as TestCase;

class UpdateUseCaseTest extends TestCase
{
    public function testCreate()
    {
        $uc = new UpdateUseCase($mock = $this->mockCompanyRepository());
        $id = $this->id(); 
        $tenant = $this->id();

        $ret = $this->mock(fn() => $uc->handle(new Input($id, $tenant, 'test')), [
            [
                'mock' => $mock,
                'action' => 'find',
                'return' => CompanyEntity::create($tenant, 'test2', 0, null, $id),
            ],
            [
                'mock' => $mock,
                'action' => 'update',
                'return' => true,
            ]
        ]);
        $this->assertInstanceOf(Output::class, $ret);
    }
}
