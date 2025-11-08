<?php

use App\Http\Controllers\SkillController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        $user = auth()->user();

        $totalLevel = $user->userSkills()->sum('level');
        $totalXp = $user->userSkills()->sum('experience');
        $skillsTracked = $user->userSkills()->count();

        $topSkills = $user->userSkills()
            ->with('skill')
            ->orderBy('level', 'desc')
            ->take(5)
            ->get()
            ->map(function ($userSkill) {
                return [
                    'skill' => $userSkill->skill,
                    'level' => $userSkill->level,
                    'experience' => $userSkill->experience,
                ];
            });

        $recentSessions = $user->timeEntries()
            ->with('skill')
            ->latest()
            ->take(5)
            ->get();

        return Inertia::render('Dashboard', [
            'stats' => [
                'totalLevel' => $totalLevel,
                'totalXp' => $totalXp,
                'skillsTracked' => $skillsTracked,
            ],
            'topSkills' => $topSkills,
            'recentSessions' => $recentSessions,
        ]);
    })->name('dashboard');

    // Skills routes
    Route::get('/skills', [SkillController::class, 'index'])->name('skills.index');
    Route::get('/skills/my-skills', [SkillController::class, 'mySkills'])->name('skills.my-skills');
    Route::get('/skills/{skill}', [SkillController::class, 'show'])->name('skills.show');
});

require __DIR__.'/settings.php';
