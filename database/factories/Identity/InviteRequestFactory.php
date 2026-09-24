<?php

namespace Database\Factories\Identity;

use App\Models\Identity\InviteRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InviteRequest>
 */
class InviteRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'whatsapp' => $this->faker->numerify('55###########'),
            'status' => InviteRequest::STATUS_PENDING,
        ];
    }
}
