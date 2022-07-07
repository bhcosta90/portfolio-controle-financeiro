<?php

namespace Database\Factories;

use App\Models\Relationship;
use App\Models\Tenant;
use Core\Application\Charge\Modules\Payment\Domain\PaymentEntity;
use Core\Application\Charge\Modules\Receive\Domain\ReceiveEntity;
use Core\Application\Charge\Shared\Enums\ChargeStatusEnum;
use Core\Application\Charge\Shared\Enums\ChargeTypeEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Charge>
 */
class ChargeFactory extends Factory
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
            'group_id' => str()->uuid(),
            'relationship_id' => Relationship::factory(),
            'relationship_type' => 'Relationship',
            'entity' => $this->faker->randomElement([PaymentEntity::class, ReceiveEntity::class]),
            'title' => $this->faker->name(),
            'value_charge' => rand(10000, 100000) / 100,
            'type' => $this->faker->randomElement(ChargeTypeEnum::toArray()),
            'status' => ChargeStatusEnum::PENDING,
            'date' => $this->faker->date(),
            'parcel_actual' => 1,
            'parcel_total' => 1,
        ];
    }
}
