<?php

use App\Models\Flashcard;
use App\Models\Skill;
use App\Models\TimeEntry;
use App\Models\User;
use App\Models\UserSkill;

test('authenticated user can untrack their own skill', function () {
    $user = User::factory()->create();
    $skill = Skill::factory()->create();
    $userSkill = UserSkill::factory()->create([
        'user_id' => $user->id,
        'skill_id' => $skill->id,
    ]);

    $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);

    $this->actingAs($user)
        ->from("/skills/{$skill->id}")
        ->delete("/user-skills/{$userSkill->id}")
        ->assertRedirect('/skills');

    expect(UserSkill::find($userSkill->id))->toBeNull();
});

test('authenticated user cannot untrack another users skill', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $skill = Skill::factory()->create();
    $otherUserSkill = UserSkill::factory()->create([
        'user_id' => $otherUser->id,
        'skill_id' => $skill->id,
    ]);

    $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);

    $this->actingAs($user)
        ->from("/skills/{$skill->id}")
        ->delete("/user-skills/{$otherUserSkill->id}")
        ->assertForbidden();

    expect(UserSkill::find($otherUserSkill->id))->not->toBeNull();
});

test('untracking a skill cascades to delete flashcards', function () {
    $user = User::factory()->create();
    $skill = Skill::factory()->create();
    $userSkill = UserSkill::factory()->create([
        'user_id' => $user->id,
        'skill_id' => $skill->id,
    ]);
    $flashcard = Flashcard::factory()->create([
        'user_skill_id' => $userSkill->id,
    ]);

    $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);

    $this->actingAs($user)
        ->from("/skills/{$skill->id}")
        ->delete("/user-skills/{$userSkill->id}")
        ->assertRedirect('/skills');

    expect(UserSkill::find($userSkill->id))->toBeNull();
    expect(Flashcard::find($flashcard->id))->toBeNull();
});

test('untracking a skill cascades to delete time entries', function () {
    $user = User::factory()->create();
    $skill = Skill::factory()->create();
    $userSkill = UserSkill::factory()->create([
        'user_id' => $user->id,
        'skill_id' => $skill->id,
    ]);
    $timeEntry = TimeEntry::factory()->create([
        'user_id' => $user->id,
        'skill_id' => $skill->id,
        'user_skill_id' => $userSkill->id,
    ]);

    $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);

    $this->actingAs($user)
        ->from("/skills/{$skill->id}")
        ->delete("/user-skills/{$userSkill->id}")
        ->assertRedirect('/skills');

    expect(UserSkill::find($userSkill->id))->toBeNull();
    expect(TimeEntry::find($timeEntry->id))->toBeNull();
});
