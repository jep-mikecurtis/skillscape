<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use App\Models\UserSkill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    /**
     * Get all available skills
     */
    public function index()
    {
        $skills = Skill::where('is_active', true)->get();
        
        return response()->json($skills);
    }

    /**
     * Get user's skills with progress
     */
    public function userSkills(Request $request)
    {
        $userSkills = $request->user()
            ->userSkills()
            ->with('skill')
            ->get()
            ->map(function ($userSkill) {
                return [
                    'id' => $userSkill->id,
                    'skill' => $userSkill->skill,
                    'level' => $userSkill->level,
                    'experience' => $userSkill->experience,
                    'experience_to_next_level' => $userSkill->experienceToNextLevel(),
                    'next_level_total_xp' => UserSkill::experienceForLevel($userSkill->level + 1),
                ];
            });

        return response()->json($userSkills);
    }

    /**
     * Start tracking a skill (creates UserSkill if needed)
     */
    public function startTracking(Request $request, Skill $skill)
    {
        $user = $request->user();

        $userSkill = UserSkill::firstOrCreate(
            [
                'user_id' => $user->id,
                'skill_id' => $skill->id,
            ],
            [
                'experience' => 0,
                'level' => 1,
            ]
        );

        return response()->json([
            'user_skill' => $userSkill,
            'skill' => $skill,
        ]);
    }
}
