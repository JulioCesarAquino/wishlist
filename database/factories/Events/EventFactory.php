<?php

namespace Database\Factories\Events;

use App\Models\Events\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = rtrim($this->faker->sentence(3), '.');

        return [
            'user_id' => User::factory(),
            'slug' => Str::slug($title).'-'.$this->faker->unique()->numberBetween(100, 999),
            'type' => $this->faker->randomElement(['casamento', 'cha_bebe', 'cha_panela', 'aniversario']),
            'title' => $title,
            'event_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'description' => $this->faker->paragraph(),
            'is_published' => true,
            'is_premium' => false,
        ];
    }
}
