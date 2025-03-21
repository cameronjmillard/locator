<?php

namespace Database\Factories;

use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'latitude' => $this->faker->latitude(-90, 90),
            'longitude' => $this->faker->longitude(-180, 180),
            'status' => $this->faker->randomElement(['open', 'closed']),
            'store_type' => $this->faker->randomElement(['takeaway', 'shop', 'restaurant']),
            'max_delivery_distance' => $this->faker->numberBetween(1, 50),
        ];
    }
}
