<?php

namespace Tests\Feature\src\Application\Charge\Modules\Payment\Repository;

use App\Models\Charge as Model;
use App\Models\Recurrence;
use App\Models\Relationship;
use App\Repository\Eloquent\ChargePaymentEloquent as Eloquent;
use Core\Application\Charge\Modules\Payment\Repository\ChargePaymentRepository as Repository;
use Core\Application\Charge\Modules\Payment\Domain\PaymentEntity as Entity;
use Core\Application\Relationship\Modules\Company\Domain\CompanyEntity;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ChargePaymentRepositoryTest extends TestCase
{
    use DatabaseMigrations;
    
    public function testInsert()
    {
        $idRelationship = Relationship::factory()->create()->id;
        $idRecurrence = Recurrence::factory()->create()->id;
        $date = date('Y-m-d');

        $obj = Entity::create('teste', 'teste', $idRelationship, $idRecurrence, 100, 0, str()->uuid(), $date);
        $this->assertTrue($this->getChargeRepository()->insert($obj));
        $this->assertDatabaseHas('charges', [
            'id' => $obj->id(),
            'title' => 'teste',
            'resume' => 'teste',
            'recurrence_id' => $idRecurrence,
            'relationship_id' => $idRelationship,
            'relationship_type' => CompanyEntity::class,
            'entity' => Entity::class,
            'value_charge' => 100,
            'value_pay' => 0,
            'type' => 2,
            'status' => 1,
            'date' => $date,
        ]);
    }

    public function testFindAndUpdate()
    {
        $idRelationship = Relationship::factory()->create()->id;
        $idRecurrence = Recurrence::factory()->create()->id;

        /** @var Entity */
        $obj = $this->getChargeRepository()->find(Model::factory()->create()->id);
        $obj->update(
            title: 'teste',
            resume: 'teste',
            company: $idRelationship,
            recurrence: $idRecurrence,
            value: 50,
            date: '2022-01-01'
        );
        $this->getChargeRepository()->update($obj);
        $this->assertDatabaseHas('charges', [
            'id' => $obj->id(),
            'title' => 'teste',
            'relationship_id' => $idRelationship,
            'recurrence_id' => $idRecurrence,
            'value_charge' => 50,
            'date' => '2022-01-01'
        ]);
    }

    public function testDelete(){
        $obj = $this->getChargeRepository()->find(Model::factory()->create()->id);
        $this->assertTrue($this->getChargeRepository()->delete($obj));
    }

    public function testPaginate()
    {
        Model::factory(35)->create(['entity' => Entity::class]);
        $data = $this->getChargeRepository()->paginate();
        $this->assertCount(15, $data->items());
        $this->assertEquals(35, $data->total());
    }

    public function testFilterTitle()
    {
        Model::factory(5)->create(['entity' => Entity::class, 'title' => 'aaaaaaa']);
        Model::factory(5)->create(['entity' => Entity::class, 'title' => 'testing']);
        $data = $this->getChargeRepository()->paginate(filter: ['title' => 'test']);
        $this->assertCount(5, $data->items());
    }

    public function testFilterName()
    {
        $relationship = Relationship::factory()->create(['name' => 'testing'])->id;
        Model::factory(5)->create(['entity' => Entity::class]);
        Model::factory(5)->create(['entity' => Entity::class, 'relationship_id' => $relationship]);
        $data = $this->getChargeRepository()->paginate(filter: ['name' => 'test']);
        $this->assertCount(5, $data->items());
    }

    private function getChargeRepository(): Eloquent
    {
        return app(Repository::class);
    }
}