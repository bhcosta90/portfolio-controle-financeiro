<?php

namespace Tests\Feature\src\Application\Charge\Modules\Recurrence\Repository;

use App\Models\Recurrence as Model;
use App\Models\Tenant;
use App\Repository\Eloquent\RecurrenceEloquent as Eloquent;
use Core\Application\Charge\Modules\Recurrence\Repository\RecurrenceRepository as Repository;
use Core\Application\Charge\Modules\Recurrence\Domain\RecurrenceEntity as Entity;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class RecurrenceRepositoryTest extends TestCase
{
    use DatabaseMigrations;
    
    public function testInsert()
    {
        $tenant = Tenant::factory()->create();
        $obj = Entity::create($tenant->id, 'teste', 50);
        $this->assertTrue($this->getRecurrenceRepository()->insert($obj));
        $this->assertDatabaseHas('recurrences', [
            'id' => $obj->id(),
            'name' => 'teste',
            'days' => 50,
        ]);
    }

    public function testFindAndUpdate()
    {
        /** @var Entity */
        $obj = $this->getRecurrenceRepository()->find(Model::factory()->create()->id);
        $obj->update(name: 'teste', days: 3);
        $this->getRecurrenceRepository()->update($obj);
        $this->assertDatabaseHas('recurrences', [
            'id' => $obj->id(),
            'name' => 'teste',
            'days' => 3,
        ]);
    }

    public function testDelete(){
        $obj = $this->getRecurrenceRepository()->find(Model::factory()->create()->id);
        $this->assertTrue($this->getRecurrenceRepository()->delete($obj));
    }

    public function testPaginate(){
        Model::factory(35)->create();
        $data = $this->getRecurrenceRepository()->paginate();
        $this->assertCount(15, $data->items());
        $this->assertEquals(35, $data->total());
    }

    public function testFilterName(){
        Model::factory(5)->create(['name' => 'aaaaaaa']);
        Model::factory(5)->create(['name' => 'testing']);
        $data = $this->getRecurrenceRepository()->paginate(filter: ['name' => 'test']);
        $this->assertCount(5, $data->items());
    }

    private function getRecurrenceRepository(): Eloquent
    {
        return app(Repository::class);
    }
}
