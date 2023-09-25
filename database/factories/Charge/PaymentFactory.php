<?php

namespace Database\Factories\Charge;

use App\Models\Charge\Charge;
use App\Models\Charge\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Payment $payment) {
            $payment->charge()->create(Charge::factory()->make()->toArray() + [
                'tenant_id' => $payment->tenant_id,
                'charge_id' => $payment->id,
                'charge_type' => get_class($payment),
            ]);
        });
    }
}
