<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFlashcardRequest;
use App\Models\Flashcard;
use App\Models\Skill;
use App\Models\UserSkill;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FlashcardController extends Controller
{
    use AuthorizesRequests;

    public function index(Skill $skill): Response
    {
        $userSkill = UserSkill::where('user_id', auth()->id())
            ->where('skill_id', $skill->id)
            ->firstOrFail();

        $flashcards = $userSkill->flashcards()
            ->latest()
            ->get()
            ->map(function ($flashcard) {
                return [
                    'id' => $flashcard->id,
                    'question' => $flashcard->question,
                    'answer' => $flashcard->answer,
                    'times_studied' => $flashcard->times_studied,
                    'times_correct' => $flashcard->times_correct,
                    'accuracy' => $flashcard->accuracyPercentage(),
                    'last_studied_at' => $flashcard->last_studied_at?->format('M j, Y'),
                    'created_at' => $flashcard->created_at->format('M j, Y'),
                ];
            });

        return Inertia::render('Skills/Flashcards/Index', [
            'skill' => $skill,
            'userSkill' => [
                'id' => $userSkill->id,
                'level' => $userSkill->level,
                'experience' => $userSkill->experience,
            ],
            'flashcards' => $flashcards,
        ]);
    }

    public function store(Skill $skill, StoreFlashcardRequest $request): RedirectResponse
    {
        $userSkill = UserSkill::where('user_id', auth()->id())
            ->where('skill_id', $skill->id)
            ->firstOrFail();

        $userSkill->flashcards()->create($request->validated());

        return redirect()->back()->with('success', 'Flashcard created successfully!');
    }

    public function update(Skill $skill, Flashcard $flashcard, StoreFlashcardRequest $request): RedirectResponse
    {
        $this->authorize('update', $flashcard);

        $flashcard->update($request->validated());

        return redirect()->back()->with('success', 'Flashcard updated successfully!');
    }

    public function destroy(Skill $skill, Flashcard $flashcard): RedirectResponse
    {
        $this->authorize('delete', $flashcard);

        $flashcard->delete();

        return redirect()->back()->with('success', 'Flashcard deleted successfully!');
    }

    public function study(Skill $skill): Response
    {
        $userSkill = UserSkill::where('user_id', auth()->id())
            ->where('skill_id', $skill->id)
            ->firstOrFail();

        $flashcards = $userSkill->flashcards()
            ->inRandomOrder()
            ->get()
            ->map(function ($flashcard) {
                return [
                    'id' => $flashcard->id,
                    'question' => $flashcard->question,
                    'answer' => $flashcard->answer,
                    'times_studied' => $flashcard->times_studied,
                    'accuracy' => $flashcard->accuracyPercentage(),
                ];
            });

        return Inertia::render('Skills/Flashcards/Study', [
            'skill' => $skill,
            'userSkill' => [
                'id' => $userSkill->id,
                'level' => $userSkill->level,
                'experience' => $userSkill->experience,
            ],
            'flashcards' => $flashcards,
        ]);
    }

    public function recordAnswer(Skill $skill, Flashcard $flashcard, Request $request): RedirectResponse
    {
        $this->authorize('update', $flashcard);

        $request->validate([
            'correct' => ['required', 'boolean'],
        ]);

        $flashcard->markStudied($request->boolean('correct'));

        return redirect()->back();
    }
}
