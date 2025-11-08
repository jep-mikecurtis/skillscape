<?php

use App\Models\Achievement;
use App\Models\Skill;
use App\Models\TimeEntry;
use App\Models\User;
use App\Models\UserSkill;

it('allows users to manually log time entries', function () {
    $user = User::factory()->create();
    $user->markEmailAsVerified();
    $skill = Skill::factory()->create(['xp_rate' => 10]);

    $completedAt = now()->subHours(2);

    $response = $this->actingAs($user)->postJson('/api/time-entries/log-manual', [
        'skill_id' => $skill->id,
        'duration_minutes' => 60,
        'completed_at' => $completedAt->toDateTimeString(),
        'notes' => 'Practiced for an hour',
    ]);

    $response->assertSuccessful();

    expect(TimeEntry::count())->toBe(1);

    $entry = TimeEntry::first();
    expect($entry->user_id)->toBe($user->id);
    expect($entry->skill_id)->toBe($skill->id);
    expect($entry->duration_minutes)->toBe(60);
    expect($entry->experience_gained)->toBe(600); // 60 min * 10 xp/min
    expect($entry->notes)->toBe('Practiced for an hour');
    expect($entry->ended_at)->not->toBeNull();

    $userSkill = UserSkill::first();
    expect($userSkill->experience)->toBe(600);
});

it('validates duration limits for manual entries', function () {
    $user = User::factory()->create();
    $user->markEmailAsVerified();
    $skill = Skill::factory()->create();

    // Test minimum
    $response = $this->actingAs($user)->postJson('/api/time-entries/log-manual', [
        'skill_id' => $skill->id,
        'duration_minutes' => 0,
        'completed_at' => now()->toDateTimeString(),
    ]);
    $response->assertStatus(422);

    // Test maximum (over 8 hours)
    $response = $this->actingAs($user)->postJson('/api/time-entries/log-manual', [
        'skill_id' => $skill->id,
        'duration_minutes' => 500,
        'completed_at' => now()->toDateTimeString(),
    ]);
    $response->assertStatus(422);
});

it('enforces daily limit of 12 hours per skill', function () {
    $user = User::factory()->create();
    $user->markEmailAsVerified();
    $skill = Skill::factory()->create(['xp_rate' => 10]);

    $userSkill = UserSkill::factory()->create([
        'user_id' => $user->id,
        'skill_id' => $skill->id,
        'experience' => 0,
        'level' => 1,
    ]);

    // First entry: 8 hours
    TimeEntry::factory()->create([
        'user_id' => $user->id,
        'skill_id' => $skill->id,
        'user_skill_id' => $userSkill->id,
        'started_at' => now()->subHours(9),
        'ended_at' => now()->subHours(1),
        'duration_minutes' => 480,
        'experience_gained' => 4800,
    ]);

    // Try to add another 5 hours (total would be 13 hours)
    $response = $this->actingAs($user)->postJson('/api/time-entries/log-manual', [
        'skill_id' => $skill->id,
        'duration_minutes' => 300,
        'completed_at' => now()->toDateTimeString(),
    ]);

    $response->assertStatus(422);
    $response->assertJson([
        'error' => 'Daily limit exceeded. You can only log up to 12 hours per skill per day.',
    ]);
});

it('validates that completed date is within the last 7 days', function () {
    $user = User::factory()->create();
    $user->markEmailAsVerified();
    $skill = Skill::factory()->create();

    // Try to log time from 8 days ago
    $response = $this->actingAs($user)->postJson('/api/time-entries/log-manual', [
        'skill_id' => $skill->id,
        'duration_minutes' => 60,
        'completed_at' => now()->subDays(8)->toDateTimeString(),
    ]);

    $response->assertStatus(422);
});

it('validates that completed date is not in the future', function () {
    $user = User::factory()->create();
    $user->markEmailAsVerified();
    $skill = Skill::factory()->create();

    // Try to log time in the future
    $response = $this->actingAs($user)->postJson('/api/time-entries/log-manual', [
        'skill_id' => $skill->id,
        'duration_minutes' => 60,
        'completed_at' => now()->addHours(1)->toDateTimeString(),
    ]);

    $response->assertStatus(422);
});

it('creates achievement when manual entry causes level up', function () {
    $user = User::factory()->create();
    $user->markEmailAsVerified();
    $skill = Skill::factory()->create(['xp_rate' => 100]);

    // Create user skill at level 1 with XP close to level 2
    $userSkill = UserSkill::factory()->create([
        'user_id' => $user->id,
        'skill_id' => $skill->id,
        'experience' => 80, // Close to level 2 (83 XP needed)
        'level' => 1,
    ]);

    $response = $this->actingAs($user)->postJson('/api/time-entries/log-manual', [
        'skill_id' => $skill->id,
        'duration_minutes' => 1, // Will gain 100 XP, pushing to level 2+
        'completed_at' => now()->toDateTimeString(),
    ]);

    $response->assertSuccessful();
    $response->assertJsonStructure(['level_up']);

    expect(Achievement::count())->toBe(1);

    $achievement = Achievement::first();
    expect($achievement->user_id)->toBe($user->id);
    expect($achievement->skill_id)->toBe($skill->id);
    expect($achievement->type)->toBe('level_up');
    expect($achievement->level_reached)->toBeGreaterThan(1);
});

it('requires authentication for manual logging', function () {
    $skill = Skill::factory()->create();

    $response = $this->postJson('/api/time-entries/log-manual', [
        'skill_id' => $skill->id,
        'duration_minutes' => 60,
        'completed_at' => now()->toDateTimeString(),
    ]);

    $response->assertUnauthorized();
});
