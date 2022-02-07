<?php

namespace Tests\Feature\Controller\Api;

use App\Models\Charge;
use Costa\LaravelTests\Api\TestValidation;
use App\Models\Cost as Model;
use Tests\TestCase;

class InvalidationCostControllerTest extends TestCase
{
    use TestValidation;

    private Model $model;

    private $endpoint = '/api/cost';

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = Model::factory()->create();
        Charge::factory()->create([
            'chargeable_id' => $this->model->id,
            'chargeable_type' => get_class($this->model),
            'basecharge_id' => $this->model->id,
            'basecharge_type' => get_class($this->model),
            'type' => null,
        ]);
    }

    public function testFieldsRequired()
    {
        $data = [
            "resume" => '',
            "value" => '',
            "customer_name" => '',
            "due_date" => '',
        ];
        $this->assertInvalidationStore($data, "required");
        $this->assertInvalidationUpdate($data, "required");
    }

    public function testFieldsNumeric()
    {
        $data = [
            "value" => 'a',
        ];
        $this->assertInvalidationStore($data + ['parcel_total' => 'a'], "numeric");
        $this->assertInvalidationUpdate($data, "numeric");
    }

    public function testFieldsMin()
    {
        $data = [
            "value" => -1,
        ];
        $this->assertInvalidationStore($data, "min.numeric", ['min' => 0]);
        $this->assertInvalidationUpdate($data, "min.numeric", ['min' => 0]);

        $data = [
            'customer_name' => 'a',
            'resume' => 'a',
        ];
        $this->assertInvalidationStore($data, "min.string", ['min' => 3]);
        $this->assertInvalidationUpdate($data, "min.string", ['min' => 3]);

        $data = [
            "parcel_total" => -1,
        ];
        $this->assertInvalidationStore($data, "min.numeric", ['min' => 1]);
    }

    public function testFieldsMax()
    {
        $data = [
            "value" => 1000000000,
        ];
        $this->assertInvalidationStore($data, "max.numeric", ['max' => 999999999]);
        $this->assertInvalidationUpdate($data, "max.numeric", ['max' => 999999999]);

        $data = [
            'customer_name' => str_repeat('a', 250),
        ];
        $this->assertInvalidationStore($data, "max.string", ['max' => 150]);
        $this->assertInvalidationUpdate($data, "max.string", ['max' => 150]);

        $data = [
            'description' => str_repeat('a', 1200),
        ];
        $this->assertInvalidationStore($data, "max.string", ['max' => 1000]);
        $this->assertInvalidationUpdate($data, "max.string", ['max' => 1000]);

        $data = [
            'parcel_total' => 500,
        ];
        $this->assertInvalidationStore($data, "max.numeric", ['max' => 360]);
    }

    public function testFieldsDateFormat()
    {
        $data = [
            "due_date" => '2021',
        ];
        $this->assertInvalidationStore($data, "date_format", ['format' => 'd/m/Y']);
        $this->assertInvalidationUpdate($data, "date_format", ['format' => 'd/m/Y']);
    }

    public function testFieldsIn()
    {
        $data = [
            "type" => '2021',
        ];

        $this->assertInvalidationStore($data, "in");
        $this->assertInvalidationUpdate($data, "in");
    }

    private function routeStore()
    {
        return $this->endpoint;
    }

    private function routePut()
    {
        return $this->endpoint . '/' . $this->model->charge->uuid;
    }
}
