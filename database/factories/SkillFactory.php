<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Skill>
 */
class SkillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['Physical', 'Creative', 'Professional', 'Knowledge', 'Language', 'Life', 'Wellness'];
        $icons = ['🏃', '💪', '🎸', '🎨', '💻', '📚', '🇪🇸', '🍳', '🧘'];

        return [
            'name' => fake()->words(2, true),
            'description' => fake()->sentence(),
            'icon' => fake()->randomElement($icons),
            'category' => fake()->randomElement($categories),
            'xp_rate' => fake()->numberBetween(8, 15),
            'is_active' => true,
        ];
    }
}
