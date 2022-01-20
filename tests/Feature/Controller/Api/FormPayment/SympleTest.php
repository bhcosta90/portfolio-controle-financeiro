<?php

namespace Tests\Feature\Controller\Api\FormPayment;

use Illuminate\Foundation\Testing\WithFaker;
use App\Http\Resources\FormPaymentResource;
use Tests\TestCase;
use App\Models\FormPayment as Model;
use App\Models\User;
use Costa\LaravelTests\Api\TestResource;
use Costa\LaravelTests\Api\TestSave;
use Exception;

class SympleTest extends TestCase
{
    use TestSave, TestResource, WithFaker;

    private Model $model;

    private $endpoint = '/api/payment';

    private array $sendData = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->sendData = [
            'name' => $this->faker->lastName(),
            'type' => 'App\FormPayment\SimpleFormPayment'
        ];

        $this->model = Model::factory()->create([
            'user_id' => self::$user->id,
            'type' => 'App\FormPayment\SimpleFormPayment',
            'active' => true
        ]);
    }

    public function testIndex()
    {
        $response = $this->getJson($this->endpoint);
        $response->assertStatus(200);
        $resource = FormPaymentResource::collection([$this->model]);
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

    public function testStore()
    {
        $dataSend = $this->sendData + ['active' => true];

        $response = $this->assertStore($dataSend, [], $this->sendData);
        $this->assertDatabaseHas('form_payments', ['uuid' => $this->getIdFromResponse($response)] + $dataSend);
        $this->assertDatabaseHas('form_payments', ['uuid' => $this->model->uuid, 'active' => false]);
    }

    private function routeStore()
    {
        return $this->endpoint;
    }

    private function routePut()
    {
        throw new Exception('route put do not implemented');
    }

    protected function model()
    {
        return new Model;
    }
}
