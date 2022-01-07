<?php

namespace Tests\Feature\Controller\Api;

use App\Models\Charge;
use Costa\LaravelTests\Api\TestValidation;
use App\Models\Cost as Model;
use Tests\TestCase;

class CostInvalidationControllerTest extends TestCase
{
    use TestValidation;

    private Model $model;

    private $endpoint = '/api/cost';

    public function testFieldsRequired() {
        $data = [
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
        $this->assertInvalidationStore($data, "numeric");
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
        ];
        $this->assertInvalidationStore($data, "min.string", ['min' => 3]);
        $this->assertInvalidationUpdate($data, "min.string", ['min' => 3]);
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
        $this->model = Model::factory()->create();
        Charge::factory()->create([
            'chargeable_id' => $this->model->id,
            'chargeable_type' => get_class($this->model),
            'type' => null,
        ]);
        return $this->endpoint . '/' . $this->model->charge->uuid;
    }
}
