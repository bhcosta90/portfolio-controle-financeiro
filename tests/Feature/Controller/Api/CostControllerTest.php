<?php

namespace Tests\Feature\Controller\Api;

use App\Http\Resources\CostResource;
use App\Models\Charge;
use App\Models\Cost;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;
use Costa\LaravelTests\Api\TestSave;
use Costa\LaravelTests\Api\TestResource;

class CostControllerTest extends TestCase
{
    use TestSave, TestResource;

    private $endpoint = '/api/cost';

    private array $sendData = [];

    private Cost $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sendData = [
            'value' => 100,
            'customer_name' => 'teste',
            'due_date' => (new Carbon())->format('d/m/Y'),
            'type' => null,
        ];

        $this->model = Cost::factory()->create();
        Charge::factory()->create([
            'chargeable_id' => $this->model->id,
            'chargeable_type' => get_class($this->model),
            'type' => null,
            'user_id' => self::$user->id,
        ]);
    }

    public function testIndex()
    {
        $response = $this->getJson($this->endpoint);
        $response->assertStatus(200);
        $resource = CostResource::collection([$this->model]);
        $this->assertResource($response, $resource);
    }

    public function testIndexOtherUser()
    {
        /** @var \Illuminate\Foundation\Auth\User */
        $user = User::factory()->create();
        $this->be($user);

        $response = $this->getJson($this->endpoint);
        $this->assertCount(0, $response->json('data'));
    }

    public function testShow()
    {
        $response = $this->getJson($this->endpoint . '/' . $this->model->charge->uuid);
        $response->assertStatus(200);
        $resource = new CostResource($this->model);
        $this->assertResource($response, $resource);
    }

    public function testDelete()
    {
        $response = $this->deleteJson($this->endpoint . '/' . $this->model->charge->uuid);
        $response->assertStatus(204);

        $this->assertDatabaseMissing('costs', [
            'id' => $this->model->id,
        ]);

        $this->assertDatabaseMissing('charges', [
            'chargeable_id' => $this->model->id,
            'chargeable_type' => get_class($this->model),
            'deleted_at' => null
        ]);
    }

    public function testStore()
    {
        $response = $this->assertStore($this->sendData, [], $this->sendData);
        $datas = $response->json('data');
        $this->assertCount(1, $datas);
        foreach ($datas as $data) {
            $resource = CostResource::collection([$this->getModelPassedUuidCharge($data['id'])]);
            $this->assertResource($response, $resource);
        }
    }

    public function testUpdate()
    {
        $datas = [
            ['customer_name' => 'bruno'] + $this->sendData,
            $this->sendData,
            ['customer_name' => 'costa'] + $this->sendData,
        ];

        foreach ($datas as $data) {
            $response = $this->assertUpdate($data, [], $data);
            $resource = new CostResource($this->getModelPassedUuidCharge($this->getIdFromResponse($response)));
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
        return new Cost;
    }

    private function getModelPassedUuidCharge(string $uuid)
    {
        return Charge::where('uuid', $uuid)->first()->chargeable;
    }
}
