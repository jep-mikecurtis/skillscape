<?php

namespace Database\Factories;

use App\Models\Skill;
use App\Models\User;
use App\Models\UserSkill;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TimeEntry>
 */
class TimeEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startedAt = $this->faker->dateTimeBetween('-1 week', 'now');
        $endedAt = $this->faker->dateTimeBetween($startedAt, 'now');
        $durationMinutes = $startedAt->diff($endedAt)->i + ($startedAt->diff($endedAt)->h * 60);

        return [
            'user_id' => User::factory(),
            'skill_id' => Skill::factory(),
            'user_skill_id' => UserSkill::factory(),
            'started_at' => $startedAt,
            'ended_at' => $endedAt,
            'duration_minutes' => $durationMinutes,
            'experience_gained' => $durationMinutes * 10,
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
