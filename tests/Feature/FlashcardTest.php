<?php

use App\Models\Flashcard;
use App\Models\Skill;
use App\Models\User;
use App\Models\UserSkill;

test('authenticated user can view flashcards page', function () {
    $user = User::factory()->create();
    $skill = Skill::factory()->create();
    $userSkill = UserSkill::factory()->create([
        'user_id' => $user->id,
        'skill_id' => $skill->id,
    ]);

    $this->actingAs($user)
        ->get("/skills/{$skill->id}/flashcards")
        ->assertSuccessful()
        ->assertInertia(fn ($page) => $page
            ->component('Skills/Flashcards/Index')
            ->has('skill')
            ->has('userSkill')
            ->has('flashcards')
        );
});

test('authenticated user can create a flashcard', function () {
    $user = User::factory()->create();
    $skill = Skill::factory()->create();
    $userSkill = UserSkill::factory()->create([
        'user_id' => $user->id,
        'skill_id' => $skill->id,
    ]);

    $this->actingAs($user)
        ->from("/skills/{$skill->id}/flashcards")
        ->post("/skills/{$skill->id}/flashcards", [
            'question' => 'What is the capital of France?',
            'answer' => 'Paris',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('flashcards', [
        'user_skill_id' => $userSkill->id,
        'question' => 'What is the capital of France?',
        'answer' => 'Paris',
    ]);
});

test('authenticated user can update their own flashcard', function () {
    $user = User::factory()->create();
    $skill = Skill::factory()->create();
    $userSkill = UserSkill::factory()->create([
        'user_id' => $user->id,
        'skill_id' => $skill->id,
    ]);
    $flashcard = Flashcard::factory()->create([
        'user_skill_id' => $userSkill->id,
        'question' => 'Old question',
        'answer' => 'Old answer',
    ]);

    $this->actingAs($user)
        ->from("/skills/{$skill->id}/flashcards")
        ->patch("/skills/{$skill->id}/flashcards/{$flashcard->id}", [
            'question' => 'New question',
            'answer' => 'New answer',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('flashcards', [
        'id' => $flashcard->id,
        'question' => 'New question',
        'answer' => 'New answer',
    ]);
});

test('authenticated user cannot update another users flashcard', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $skill = Skill::factory()->create();
    $otherUserSkill = UserSkill::factory()->create([
        'user_id' => $otherUser->id,
        'skill_id' => $skill->id,
    ]);
    $flashcard = Flashcard::factory()->create([
        'user_skill_id' => $otherUserSkill->id,
    ]);

    $this->actingAs($user)
        ->from("/skills/{$skill->id}/flashcards")
        ->patch("/skills/{$skill->id}/flashcards/{$flashcard->id}", [
            'question' => 'Hacked question',
            'answer' => 'Hacked answer',
        ])
        ->assertForbidden();
});

test('authenticated user can delete their own flashcard', function () {
    $user = User::factory()->create();
    $skill = Skill::factory()->create();
    $userSkill = UserSkill::factory()->create([
        'user_id' => $user->id,
        'skill_id' => $skill->id,
    ]);
    $flashcard = Flashcard::factory()->create([
        'user_skill_id' => $userSkill->id,
    ]);

    $this->actingAs($user)
        ->from("/skills/{$skill->id}/flashcards")
        ->delete("/skills/{$skill->id}/flashcards/{$flashcard->id}")
        ->assertRedirect();

    expect(Flashcard::find($flashcard->id))->toBeNull();
});

test('authenticated user can view study mode', function () {
    $user = User::factory()->create();
    $skill = Skill::factory()->create();
    $userSkill = UserSkill::factory()->create([
        'user_id' => $user->id,
        'skill_id' => $skill->id,
    ]);

    $this->actingAs($user)
        ->get("/skills/{$skill->id}/flashcards/study")
        ->assertSuccessful()
        ->assertInertia(fn ($page) => $page
            ->component('Skills/Flashcards/Study')
            ->has('skill')
            ->has('userSkill')
            ->has('flashcards')
        );
});

test('flashcard statistics are tracked correctly', function () {
    $user = User::factory()->create();
    $skill = Skill::factory()->create();
    $userSkill = UserSkill::factory()->create([
        'user_id' => $user->id,
        'skill_id' => $skill->id,
    ]);
    $flashcard = Flashcard::factory()->create([
        'user_skill_id' => $userSkill->id,
        'times_studied' => 0,
        'times_correct' => 0,
    ]);

    // Mark as correct
    $flashcard->markStudied(true);
    expect($flashcard->times_studied)->toBe(1);
    expect($flashcard->times_correct)->toBe(1);
    expect($flashcard->accuracyPercentage())->toBe(100);

    // Mark as incorrect
    $flashcard->markStudied(false);
    expect($flashcard->times_studied)->toBe(2);
    expect($flashcard->times_correct)->toBe(1);
    expect($flashcard->accuracyPercentage())->toBe(50);
});

test('question is required when creating flashcard', function () {
    $user = User::factory()->create();
    $skill = Skill::factory()->create();
    $userSkill = UserSkill::factory()->create([
        'user_id' => $user->id,
        'skill_id' => $skill->id,
    ]);

    $this->actingAs($user)
        ->from("/skills/{$skill->id}/flashcards")
        ->post("/skills/{$skill->id}/flashcards", [
            'question' => '',
            'answer' => 'Paris',
        ])
        ->assertSessionHasErrors('question');
});

test('answer is required when creating flashcard', function () {
    $user = User::factory()->create();
    $skill = Skill::factory()->create();
    $userSkill = UserSkill::factory()->create([
        'user_id' => $user->id,
        'skill_id' => $skill->id,
    ]);

    $this->actingAs($user)
        ->from("/skills/{$skill->id}/flashcards")
        ->post("/skills/{$skill->id}/flashcards", [
            'question' => 'What is the capital of France?',
            'answer' => '',
        ])
        ->assertSessionHasErrors('answer');
});
