<?php

namespace Database\Factories;

use App\Enums\TodoState;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Todo>
 */
class TodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'state' => TodoState::PENDING,
        ];
    }

    public function pending(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => TodoState::PENDING,
            ];
        });
    }

    public function done(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => TodoState::DONE,
            ];
        });
    }

    public function archive(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => TodoState::ARCHIVED,
            ];
        });
    }
}
