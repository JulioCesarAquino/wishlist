<?php

namespace Database\Factories\Catalog;

use App\Models\Catalog\ProductTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductTemplate>
 */
class ProductTemplateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => rtrim($this->faker->sentence(2), '.'),
            'category' => $this->faker->randomElement(['Cozinha', 'Casa', 'Decoração', 'Contribuição']),
            'description' => $this->faker->sentence(),
            'suggested_price' => $this->faker->randomFloat(2, 30, 900),
        ];
    }
}
