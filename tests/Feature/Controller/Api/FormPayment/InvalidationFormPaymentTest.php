<?php

namespace Tests\Feature\Controller\Api\FormPayment;

use Tests\TestCase;
use App\Models\FormPayment as Model;
use Costa\LaravelTests\Api\TestValidation;
use Exception;

class InvalidationFormPaymentTest extends TestCase
{
    use TestValidation;

    private Model $model;

    private $endpoint = '/api/payment';

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testFieldsRequired()
    {
        $data = [
            "type" => '',
            'name' => '',
        ];
        $this->assertInvalidationStore($data, "required");
    }

    public function testFieldsMin()
    {
        $data = [
            'name' => 'a',
        ];
        $this->assertInvalidationStore($data, "min.string", ['min' => 3]);
    }

    public function testFieldsMax()
    {
        $data = [
            'name' => str_repeat('a', 500),
        ];
        $this->assertInvalidationStore($data, "max.string", ['max' => 70]);
    }

    public function testFieldsIn()
    {
        $data = [
            "type" => '2021',
        ];

        $this->assertInvalidationStore($data, "in");
    }

    private function routeStore()
    {
        return $this->endpoint;
    }

    private function routePut()
    {
        throw new Exception('route put do not implemented');
    }
}
