<?php

namespace Database\Factories\Orders;

use App\Models\Events\Event;
use App\Models\Guests\Guest;
use App\Models\Orders\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
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
            'guest_id' => Guest::factory(),
            'status' => Order::STATUS_PENDING,
            'total_amount' => $this->faker->randomFloat(2, 30, 900),
            'message' => $this->faker->optional()->sentence(),
        ];
    }
}
