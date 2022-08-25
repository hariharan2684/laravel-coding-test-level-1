<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [

            'name' => fake()->name(),
            'slug' => fake()->slug() . '-' . strtotime("now") . '-' . rand(1, 2000000),
            'start_at' => fake()->dateTimeBetween('+1 week', '+3 weeks'),
            'end_at' => fake()->dateTimeBetween('5 weeks', '+8 weeks'),
        ];
    }
}