<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\UserSkill;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SkillController extends Controller
{
    /**
     * Display all available skills
     */
    public function index(Request $request)
    {
        $skills = Skill::where('is_active', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        $userSkills = $request->user()
            ->userSkills()
            ->pluck('skill_id')
            ->toArray();

        return Inertia::render('Skills/Index', [
            'skills' => $skills,
            'userSkillIds' => $userSkills,
        ]);
    }

    /**
     * Display user's skill progress
     */
    public function mySkills(Request $request)
    {
        $userSkills = $request->user()
            ->userSkills()
            ->with(['skill', 'timeEntries' => function ($query) {
                $query->latest()->take(5);
            }])
            ->get()
            ->map(function ($userSkill) {
                return [
                    'id' => $userSkill->id,
                    'skill' => $userSkill->skill,
                    'level' => $userSkill->level,
                    'experience' => $userSkill->experience,
                    'experience_to_next_level' => $userSkill->experienceToNextLevel(),
                    'next_level_total_xp' => UserSkill::experienceForLevel($userSkill->level + 1),
                    'current_level_xp' => UserSkill::experienceForLevel($userSkill->level),
                    'progress_percentage' => $userSkill->experienceToNextLevel() > 0
                        ? round(
                            (($userSkill->experience - UserSkill::experienceForLevel($userSkill->level)) /
                            (UserSkill::experienceForLevel($userSkill->level + 1) - UserSkill::experienceForLevel($userSkill->level))) * 100,
                            1
                        )
                        : 100,
                    'recent_sessions' => $userSkill->timeEntries,
                ];
            });

        $totalLevel = $userSkills->sum('level');
        $totalXp = $userSkills->sum('experience');

        return Inertia::render('Skills/MySkills', [
            'userSkills' => $userSkills,
            'totalLevel' => $totalLevel,
            'totalXp' => $totalXp,
        ]);
    }

    /**
     * Display individual skill detail with tracking
     */
    public function show(Request $request, Skill $skill)
    {
        $userSkill = UserSkill::firstOrCreate(
            [
                'user_id' => $request->user()->id,
                'skill_id' => $skill->id,
            ],
            [
                'experience' => 0,
                'level' => 1,
            ]
        );

        $userSkill->load(['timeEntries' => function ($query) {
            $query->latest()->take(20);
        }]);

        $activeSession = $request->user()
            ->timeEntries()
            ->whereNull('ended_at')
            ->where('skill_id', $skill->id)
            ->latest('started_at')
            ->first();

        return Inertia::render('Skills/Show', [
            'skill' => $skill,
            'userSkill' => [
                'id' => $userSkill->id,
                'level' => $userSkill->level,
                'experience' => $userSkill->experience,
                'experience_to_next_level' => $userSkill->experienceToNextLevel(),
                'next_level_total_xp' => UserSkill::experienceForLevel($userSkill->level + 1),
                'current_level_xp' => UserSkill::experienceForLevel($userSkill->level),
                'progress_percentage' => $userSkill->experienceToNextLevel() > 0
                    ? round(
                        (($userSkill->experience - UserSkill::experienceForLevel($userSkill->level)) /
                        (UserSkill::experienceForLevel($userSkill->level + 1) - UserSkill::experienceForLevel($userSkill->level))) * 100,
                        1
                    )
                    : 100,
            ],
            'recentSessions' => $userSkill->timeEntries,
            'activeSession' => $activeSession,
        ]);
    }
}
