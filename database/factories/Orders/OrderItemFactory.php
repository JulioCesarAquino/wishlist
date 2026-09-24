<?php

namespace Database\Factories\Orders;

use App\Models\Catalog\EventProduct;
use App\Models\Orders\Order;
use App\Models\Orders\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'event_product_id' => EventProduct::factory(),
            'quantity' => 1,
            'unit_price' => $this->faker->randomFloat(2, 30, 900),
        ];
    }
}
