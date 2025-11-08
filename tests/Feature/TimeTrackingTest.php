<?php

use App\Models\Skill;
use App\Models\User;

it('allows users to start time tracking for a skill', function () {
    $user = User::factory()->create();
    $skill = Skill::factory()->create();

    $response = $this->actingAs($user, 'sanctum')->postJson('/api/time-entries/start', [
        'skill_id' => $skill->id,
        'notes' => 'Test session',
    ]);

    $response->assertCreated();
    $response->assertJsonStructure([
        'time_entry' => [
            'id',
            'user_id',
            'skill_id',
            'started_at',
        ],
        'message',
    ]);

    $this->assertDatabaseHas('time_entries', [
        'user_id' => $user->id,
        'skill_id' => $skill->id,
        'notes' => 'Test session',
        'ended_at' => null,
    ]);
});

it('prevents starting a session when one is already active', function () {
    $user = User::factory()->create();
    $skill = Skill::factory()->create();

    $this->actingAs($user, 'sanctum')->postJson('/api/time-entries/start', [
        'skill_id' => $skill->id,
    ]);

    $response = $this->actingAs($user, 'sanctum')->postJson('/api/time-entries/start', [
        'skill_id' => $skill->id,
    ]);

    $response->assertStatus(422);
    $response->assertJson([
        'error' => 'You already have an active time tracking session',
    ]);
});

it('allows users to stop time tracking and awards XP', function () {
    $user = User::factory()->create();
    $skill = Skill::factory()->create(['xp_rate' => 10]);

    $this->actingAs($user, 'sanctum')->postJson('/api/time-entries/start', [
        'skill_id' => $skill->id,
    ]);

    sleep(1);

    $response = $this->actingAs($user, 'sanctum')->postJson('/api/time-entries/stop');

    $response->assertSuccessful();
    $response->assertJsonStructure([
        'time_entry' => [
            'duration_minutes',
            'experience_gained',
        ],
        'user_skill' => [
            'level',
            'experience',
        ],
        'message',
    ]);

    $this->assertDatabaseHas('time_entries', [
        'user_id' => $user->id,
        'skill_id' => $skill->id,
    ]);

    $this->assertDatabaseMissing('time_entries', [
        'user_id' => $user->id,
        'ended_at' => null,
    ]);
});

it('returns 404 when trying to stop without an active session', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')->postJson('/api/time-entries/stop');

    $response->assertNotFound();
    $response->assertJson([
        'error' => 'No active time tracking session found',
    ]);
});

it('retrieves active time entry', function () {
    $user = User::factory()->create();
    $skill = Skill::factory()->create();

    $this->actingAs($user, 'sanctum')->postJson('/api/time-entries/start', [
        'skill_id' => $skill->id,
    ]);

    $response = $this->actingAs($user, 'sanctum')->getJson('/api/time-entries/active');

    $response->assertSuccessful();
    $response->assertJsonStructure([
        'active_entry' => [
            'id',
            'skill',
            'started_at',
        ],
        'elapsed_minutes',
    ]);
});

it('requires authentication for time tracking', function () {
    $skill = Skill::factory()->create();

    $response = $this->postJson('/api/time-entries/start', [
        'skill_id' => $skill->id,
    ]);

    $response->assertUnauthorized();
});
