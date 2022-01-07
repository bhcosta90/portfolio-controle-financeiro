<?php

namespace Tests\Feature\Controller\Api;

use App\Http\Resources\IncomeResource;
use App\Models\Charge;
use App\Models\Income;
use Carbon\Carbon;
use Tests\TestCase;
use Costa\LaravelTests\Api\TestSave;
use Costa\LaravelTests\Api\TestResource;

class IncomeControllerTest extends TestCase
{
    use TestSave, TestResource;

    private $endpoint = '/api/income';

    private array $sendData = [];

    private $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sendData = [
            'value' => 100,
            'customer_name' => 'teste',
            'due_date' => (new Carbon())->format('d/m/Y'),
            'type' => null,
        ];
    }

    public function testIndex()
    {
        $obj = $this->createNewModel();

        $response = $this->getJson($this->endpoint);
        $response->assertStatus(200);
        $resource = IncomeResource::collection([$obj]);
        $this->assertResource($response, $resource);
    }

    public function testShow()
    {
        $obj = $this->createNewModel();
        $response = $this->getJson($this->endpoint . '/' . $obj->charge->uuid);
        $response->assertStatus(200);
        $resource = new IncomeResource($obj);
        $this->assertResource($response, $resource);
    }

    public function testDelete()
    {
        $obj = $this->createNewModel();

        $response = $this->deleteJson($this->endpoint . '/' . $obj->charge->uuid);
        $response->assertStatus(204);

        $this->assertDatabaseMissing('incomes', [
            'id' => $obj->id,
        ]);

        $this->assertDatabaseMissing('charges', [
            'chargeable_id' => $obj->id,
            'chargeable_type' => get_class($obj),
            'deleted_at' => null
        ]);
    }

    public function testStore()
    {
        $response = $this->assertStore($this->sendData, [], $this->sendData);
        $datas = $response->json('data');
        $this->assertCount(1, $datas);
        foreach ($datas as $data) {
            $resource = IncomeResource::collection([$this->getModelPassedUuidCharge($data['id'])]);
            $this->assertResource($response, $resource);
        }
    }

    public function testUpdate()
    {
        $this->createNewModel();

        $datas = [
            ['customer_name' => 'bruno'] + $this->sendData,
            $this->sendData,
            ['customer_name' => 'costa'] + $this->sendData,
        ];

        foreach ($datas as $data) {
            $response = $this->assertUpdate($data, [], $data);
            $resource = new IncomeResource($this->getModelPassedUuidCharge($this->getIdFromResponse($response)));
            $this->assertResource($response, $resource);
        }
    }

    protected function routeStore()
    {
        return $this->endpoint;
    }

    protected function routePut()
    {
        return $this->endpoint . '/' . $this->model->charge->uuid;
    }

    protected function model()
    {
        return new Income;
    }

    protected function createNewModel()
    {
        $obj = $this->model()->factory()->create();
        Charge::factory()->create([
            'chargeable_id' => $obj->id,
            'chargeable_type' => get_class($obj),
            'type' => null,
        ]);
        return $this->model = $obj;
    }

    private function getModelPassedUuidCharge(string $uuid)
    {
        return Charge::where('uuid', $uuid)->first()->chargeable;
    }
}
