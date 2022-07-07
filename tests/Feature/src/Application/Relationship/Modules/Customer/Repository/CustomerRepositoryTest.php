<?php

namespace Tests\Feature\src\Application\Relationship\Modules\Customer\Repository;

use App\Models\Relationship as Model;
use App\Models\Tenant;
use App\Repository\Eloquent\CustomerEloquent as Eloquent;
use Core\Application\Relationship\Modules\Customer\Domain\CustomerEntity as Entity;
use Core\Application\Relationship\Modules\Customer\Repository\CustomerRepository as Repository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CustomerRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    public function testInsert()
    {
        $tenant = Tenant::factory()->create();
        $obj = Entity::create($tenant->id, 'teste', 50);
        $this->assertTrue($this->getCustomerRepository()->insert($obj));
        $this->assertDatabaseHas('relationships', [
            'id' => $obj->id(),
            'name' => 'teste',
            'value' => 0,
            'entity' => Entity::class,
        ]);
    }

    private function getCustomerRepository(): Eloquent
    {
        return app(Repository::class);
    }

    public function testFindAndUpdate()
    {
        /** @var Entity */
        $obj = $this->getCustomerRepository()->find(Model::factory()->create()->id);
        $obj->update(name: 'teste');
        $this->getCustomerRepository()->update($obj);
        $this->assertDatabaseHas('relationships', [
            'id' => $obj->id(),
            'name' => 'teste',
            'value' => 0,
        ]);
    }

    public function testDelete()
    {
        $obj = $this->getCustomerRepository()->find(Model::factory()->create()->id);
        $this->assertTrue($this->getCustomerRepository()->delete($obj));
    }

    public function testPaginate()
    {
        Model::factory(35)->create(['entity' => Entity::class]);
        $data = $this->getCustomerRepository()->paginate();
        $this->assertCount(15, $data->items());
        $this->assertEquals(35, $data->total());
    }

    public function testFilterName()
    {
        Model::factory(5)->create(['entity' => Entity::class, 'name' => 'aaaaaaa']);
        Model::factory(5)->create(['entity' => Entity::class, 'name' => 'testing']);
        $data = $this->getCustomerRepository()->paginate(filter: ['name' => 'test']);
        $this->assertCount(5, $data->items());
    }
}
