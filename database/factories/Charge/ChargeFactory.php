<?php

namespace Database\Factories\Charge;

use App\Models\Account;
use App\Models\Charge\Charge;
use App\Models\Charge\Payment;
use App\Models\Charge\Receive;
use App\Models\Enum\Charge\TypeEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Charge\Charge>
 */
class ChargeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $chargeType = (rand(0, 1) ? Payment::create() : Receive::create());
        
        return [
            'group_id' => str()->uuid(),
            'account_id' => Account::factory(),
            'value' => $this->faker->numberBetween(100, 500),
            'charge_type' => get_class($chargeType),
            'charge_id' => $chargeType->id,
            'type' => TypeEnum::UNIQUE,
            'due_date' => $this->faker->dateTimeBetween('0 month', '1 month')->format('Y-m-d'),
        ];
    }
}
