<?php

namespace Tests\Feature\src\Application\Relationship\Modules\Company\Repository;

use App\Models\Relationship as Model;
use App\Models\Tenant;
use App\Repository\Eloquent\CompanyEloquent as Eloquent;
use Core\Application\Relationship\Modules\Company\Repository\CompanyRepository as Repository;
use Core\Application\Relationship\Modules\Company\Domain\CompanyEntity as Entity;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CompanyRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    public function testInsert()
    {
        $tenant = Tenant::factory()->create();
        $obj = Entity::create($tenant->id, 'teste', 50);
        $this->assertTrue($this->getCompanyRepository()->insert($obj));
        $this->assertDatabaseHas('relationships', [
            'id' => $obj->id(),
            'name' => 'teste',
            'value' => 0,
            'entity' => Entity::class,
        ]);
    }

    public function testFindAndUpdate()
    {
        /** @var Entity */
        $obj = $this->getCompanyRepository()->find(Model::factory()->create()->id);
        $obj->update(name: 'teste');
        $this->getCompanyRepository()->update($obj);
        $this->assertDatabaseHas('relationships', [
            'id' => $obj->id(),
            'name' => 'teste',
            'value' => 0,
        ]);
    }

    public function testDelete(){
        $obj = $this->getCompanyRepository()->find(Model::factory()->create()->id);
        $this->assertTrue($this->getCompanyRepository()->delete($obj));
    }

    public function testPaginate(){
        Model::factory(35)->create(['entity' => Entity::class]);
        $data = $this->getCompanyRepository()->paginate();
        $this->assertCount(15, $data->items());
        $this->assertEquals(35, $data->total());
    }

    public function testFilterName(){
        Model::factory(5)->create(['entity' => Entity::class, 'name' => 'aaaaaaa']);
        Model::factory(5)->create(['entity' => Entity::class, 'name' => 'testing']);
        $data = $this->getCompanyRepository()->paginate(filter: ['name' => 'test']);
        $this->assertCount(5, $data->items());
    }

    private function getCompanyRepository(): Eloquent
    {
        return app(Repository::class);
    }
}
