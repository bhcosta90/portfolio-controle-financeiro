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
            'resume' => 'oi tudo bem',
            'description' => 'oi tudo bem',
            'value' => 100,
            'customer_name' => 'teste',
            'due_date' => (new Carbon('2022-01-10'))->format('d/m/Y'),
            '_date_finish' => (new Carbon('2022-01-11'))->format('d/m/Y'),
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
        $newData = $this->sendData;
        unset($newData['_date_finish']);

        $response = $this->assertStore($this->sendData, [], $newData);
        $datas = $response->json('data');
        $this->assertCount(1, $datas);
        foreach ($datas as $data) {
            $resource = CostResource::collection([$this->getModelPassedUuidCharge($data['id'])]);
            $this->assertResource($response, $resource);
        }
    }

    public function testStoreWithParcel()
    {
        $response = $this->postJson($this->endpoint, $this->sendData + [
            'parcel_total' => 10,
        ]);

        $datas = [];
        foreach($response->json('data') as $result){
            $objModel = $this->getModelPassedUuidCharge($result['id']);
            $datas[] = $objModel;
        }

        $this->assertEquals(1, $response->json('data.0.parcel_actual'));
        $this->assertEquals(10, $response->json('data.0.parcel_total'));
        $this->assertEquals(10, $response->json('data.0.value'));

        $this->assertCount(count($datas), $response->json('data'));
        $resource = CostResource::collection($datas);
        $this->assertResource($response, $resource);

        $response = $this->postJson($this->endpoint, ['value' => 98.97] + $this->sendData + [
            'parcel_total' => 10,
        ]);
        $this->assertEquals(1, $response->json('data.0.parcel_actual'));
        $this->assertEquals(10, $response->json('data.0.parcel_total'));
        $this->assertEquals(9.89, $response->json('data.0.value'));
        $this->assertEquals(9.89, $response->json('data.1.value'));
        $this->assertEquals(9.96, $response->json('data.9.value'));

        $totalValue = 0;
        foreach($response->json('data') as $data) {
            $totalValue += $data['value'];
        }

        $this->assertEquals(98.97, $totalValue);
    }

    public function testFieldTypeFifthBusinessDay()
    {
        $response = $this->postJson($this->endpoint, ['type' => 'fifth_business_day'] + $this->sendData);
        $this->assertCount(2, $response->json('data'));
        $this->assertEquals('fifth_business_day', $response->json('data.0.type'));
        $this->assertEquals('fifth_business_day', $response->json('data.1.type'));
        $this->assertEquals(false, $response->json('data.0.future'));
        $this->assertEquals(true, $response->json('data.1.future'));
    }

    public function testFieldEvery_20th()
    {
        $response = $this->postJson($this->endpoint, ['type' => 'every_20th'] + $this->sendData);
        $this->assertCount(2, $response->json('data'));
        $this->assertEquals('every_20th', $response->json('data.0.type'));
        $this->assertEquals('every_20th', $response->json('data.1.type'));
        $this->assertEquals(false, $response->json('data.0.future'));
        $this->assertEquals(true, $response->json('data.1.future'));
    }

    public function testFieldEveryLastDay()
    {
        $response = $this->postJson($this->endpoint, ['type' => 'every_last_day'] + $this->sendData);
        $this->assertCount(2, $response->json('data'));
        $this->assertEquals('every_last_day', $response->json('data.0.type'));
        $this->assertEquals('every_last_day', $response->json('data.1.type'));
        $this->assertEquals(false, $response->json('data.0.future'));
        $this->assertEquals(true, $response->json('data.1.future'));
    }

    public function testFieldMonth()
    {
        $response = $this->postJson($this->endpoint, ['type' => 'month'] + $this->sendData);
        $this->assertCount(2, $response->json('data'));
        $this->assertEquals('month', $response->json('data.0.type'));
        $this->assertEquals('month', $response->json('data.1.type'));
        $this->assertEquals(false, $response->json('data.0.future'));
        $this->assertEquals(true, $response->json('data.1.future'));
    }

    public function testFieldWeek()
    {
        $response = $this->postJson($this->endpoint, ['type' => 'week'] + $this->sendData);
        $this->assertCount(7, $response->json('data'));
        $this->assertEquals('week', $response->json('data.0.type'));
        $this->assertEquals('week', $response->json('data.1.type'));
        $this->assertEquals(false, $response->json('data.0.future'));
        $this->assertEquals(true, $response->json('data.3.future'));
    }

    public function testFieldTwoWeek()
    {
        $response = $this->postJson($this->endpoint, ['type' => 'twoweek'] + $this->sendData);
        $this->assertCount(4, $response->json('data'));
        $this->assertEquals('twoweek', $response->json('data.0.type'));
        $this->assertEquals('twoweek', $response->json('data.1.type'));
        $this->assertEquals(false, $response->json('data.0.future'));
        $this->assertEquals(true, $response->json('data.3.future'));
    }

    public function testUpdate()
    {
        $datas = [
            ['customer_name' => 'bruno'] + $this->sendData,
            $this->sendData,
            ['customer_name' => 'costa'] + $this->sendData,
        ];

        foreach ($datas as $data) {
            $newDate = $data;
            unset($newDate['_date_finish']);
            $response = $this->assertUpdate($data, [], $newDate);
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
