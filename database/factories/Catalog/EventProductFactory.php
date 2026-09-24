<?php

namespace Database\Factories\Catalog;

use App\Models\Catalog\EventProduct;
use App\Models\Events\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EventProduct>
 */
class EventProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'name' => rtrim($this->faker->sentence(2), '.'),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 30, 900),
            'quantity_total' => 1,
            'quantity_purchased' => 0,
            'is_active' => true,
        ];
    }
}
