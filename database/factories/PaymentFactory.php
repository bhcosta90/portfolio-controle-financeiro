<?php

namespace Database\Factories;

use App\Models\Charge;
use App\Models\Tenant;
use Core\Financial\Payment\Enums\ChargeStatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => str()->uuid(),
            'tenant_id' => Tenant::factory(),
            'value' => rand(100, 1000),
            'status' => ChargeStatusEnum::PROCESSING,
            'date' => date('Y-m-d')
        ];
    }
}
