<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Flashcard>
 */
class FlashcardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_skill_id' => \App\Models\UserSkill::factory(),
            'question' => fake()->sentence().'?',
            'answer' => fake()->paragraph(),
            'times_studied' => fake()->numberBetween(0, 20),
            'times_correct' => function (array $attributes) {
                return fake()->numberBetween(0, $attributes['times_studied']);
            },
            'last_studied_at' => fake()->optional()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
