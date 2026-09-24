<?php

namespace Database\Factories\Guests;

use App\Models\Events\Event;
use App\Models\Guests\Guest;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Guest>
 */
class GuestFactory extends Factory
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
            'identifier' => (string) Str::uuid(),
            'name' => $this->faker->name(),
            'whatsapp' => $this->faker->numerify('55###########'),
            'email' => $this->faker->optional()->safeEmail(),
        ];
    }
}
