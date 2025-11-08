<?php

use App\Models\Skill;
use App\Models\User;

it('displays all active skills on the index page', function () {
    $user = User::factory()->create();
    $user->markEmailAsVerified();

    Skill::factory()->count(5)->create(['is_active' => true]);
    Skill::factory()->create(['is_active' => false]);

    $response = $this->actingAs($user)->get('/skills');

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('Skills/Index')
        ->has('skills', 5)
    );
});

it('displays user skills on my skills page', function () {
    $user = User::factory()->create();
    $user->markEmailAsVerified();
    $skill = Skill::factory()->create();

    $user->userSkills()->create([
        'skill_id' => $skill->id,
        'experience' => 1000,
        'level' => 5,
    ]);

    $response = $this->actingAs($user)->get('/skills/my-skills');

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('Skills/MySkills')
        ->has('userSkills', 1)
        ->where('totalLevel', 5)
        ->where('totalXp', 1000)
    );
});

it('displays individual skill detail page', function () {
    $user = User::factory()->create();
    $user->markEmailAsVerified();
    $skill = Skill::factory()->create();

    $response = $this->actingAs($user)->get("/skills/{$skill->id}");

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('Skills/Show')
        ->has('skill')
        ->has('userSkill')
    );
});

it('requires authentication to view skills', function () {
    $response = $this->get('/skills');
    $response->assertRedirect('/login');
});
