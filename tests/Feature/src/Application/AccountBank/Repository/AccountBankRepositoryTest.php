<?php

namespace Tests\Feature\src\Application\AccountBank\Repository;

use App\Models\AccountBank as Model;
use App\Repository\Eloquent\AccountBankEloquent as Eloquent;
use Core\Application\AccountBank\Domain\AccountBankEntity as Entity;
use Core\Application\AccountBank\Repository\AccountBankRepository as Repository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class AccountBankRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    public function testInsert()
    {
        $obj = Entity::create(Uuid::uuid4(), 'teste', 50);
        $this->assertTrue($this->getAccountBankRepository()->insert($obj));
        $this->assertDatabaseHas('account_banks', [
            'id' => $obj->id(),
            'name' => 'teste',
            'value' => 50,
            'bank_code' => null,
            'bank_agency' => null,
            'bank_agency_digit' => null,
            'bank_account' => null,
            'bank_account_digit' => null,
        ]);
    }

    public function testFindAndUpdate()
    {
        /** @var Entity */
        $obj = $this->getAccountBankRepository()->find(Model::factory()->create()->id);
        $obj->update(name: 'teste', value: 0);
        $this->assertTrue($this->getAccountBankRepository()->update($obj));
        $this->assertDatabaseHas('account_banks', [
            'id' => $obj->id(),
            'name' => 'teste',
            'value' => 0,
            'bank_code' => null,
            'bank_agency' => null,
            'bank_agency_digit' => null,
            'bank_account' => null,
            'bank_account_digit' => null,
        ]);
    }

    public function testDelete(){
        $obj = $this->getAccountBankRepository()->find(Model::factory()->create()->id);
        $this->assertTrue($this->getAccountBankRepository()->delete($obj));
    }

    public function testPaginate(){
        Model::factory(35)->create();
        $data = $this->getAccountBankRepository()->paginate();
        $this->assertCount(15, $data->items());
        $this->assertEquals(35, $data->total());
    }

    public function testFilterName(){
        Model::factory(5)->create(['name' => 'aaaaaaa']);
        Model::factory(5)->create(['name' => 'testing']);
        $data = $this->getAccountBankRepository()->paginate(filter: ['name' => 'test']);
        $this->assertCount(5, $data->items());
    }

    public function testInsertWithBank()
    {
        $obj = Entity::create(Uuid::uuid4(), 'teste', 50, 301, '500', '1', '600', 2);
        $this->getAccountBankRepository()->insert($obj);
        $this->assertDatabaseHas('account_banks', [
            'id' => $obj->id(),
            'name' => 'teste',
            'value' => 50,
            'bank_code' => 301,
            'bank_agency' => '500',
            'bank_agency_digit' => '1',
            'bank_account' => '600',
            'bank_account_digit' => 2,
        ]);
    }

    public function testUpdateWithBankEmpty()
    {
        /** @var Entity */
        $obj = $this->getAccountBankRepository()->find(Model::factory()->create()->id);
        $obj->update('teste', 0, 301, '500', '1', '600', 2);
        $this->getAccountBankRepository()->update($obj);
        $this->assertDatabaseHas('account_banks', [
            'id' => $obj->id(),
            'name' => 'teste',
            'value' => 0,
            'bank_code' => 301,
            'bank_agency' => '500',
            'bank_agency_digit' => '1',
            'bank_account' => '600',
            'bank_account_digit' => 2,
        ]);
    }

    private function getAccountBankRepository(): Eloquent
    {
        return app(Repository::class);
    }
}
