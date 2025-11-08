<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\Skill;
use App\Models\TimeEntry;
use App\Models\UserSkill;
use Illuminate\Http\Request;

class TimeEntryController extends Controller
{
    /**
     * Get user's time entries
     */
    public function index(Request $request)
    {
        $entries = $request->user()
            ->timeEntries()
            ->with('skill')
            ->orderBy('started_at', 'desc')
            ->paginate(20);

        return response()->json($entries);
    }

    /**
     * Get currently active time entry
     */
    public function active(Request $request)
    {
        $activeEntry = $request->user()->activeTimeEntry();

        if (! $activeEntry) {
            return response()->json(['active_entry' => null]);
        }

        $activeEntry->load('skill');

        return response()->json([
            'active_entry' => $activeEntry,
            'elapsed_minutes' => now()->diffInMinutes($activeEntry->started_at),
        ]);
    }

    /**
     * Start a new time tracking session
     */
    public function start(Request $request)
    {
        $request->validate([
            'skill_id' => 'required|exists:skills,id',
            'notes' => 'nullable|string',
        ]);

        $user = $request->user();

        // Check if there's an active session
        if ($user->activeTimeEntry()) {
            return response()->json([
                'error' => 'You already have an active time tracking session',
            ], 422);
        }

        $skill = Skill::findOrFail($request->skill_id);

        // Get or create UserSkill
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

        $timeEntry = TimeEntry::create([
            'user_id' => $user->id,
            'skill_id' => $skill->id,
            'user_skill_id' => $userSkill->id,
            'started_at' => now(),
            'notes' => $request->notes,
        ]);

        $timeEntry->load('skill');

        return response()->json([
            'time_entry' => $timeEntry,
            'message' => 'Time tracking started',
        ], 201);
    }

    /**
     * Stop the active time tracking session
     */
    public function stop(Request $request)
    {
        $activeEntry = $request->user()->activeTimeEntry();

        if (! $activeEntry) {
            return response()->json([
                'error' => 'No active time tracking session found',
            ], 404);
        }

        $levelUpData = $activeEntry->endSession();
        $activeEntry->load(['skill', 'userSkill']);

        $response = [
            'time_entry' => $activeEntry,
            'user_skill' => $activeEntry->userSkill,
            'message' => 'Time tracking stopped',
            'experience_gained' => $activeEntry->experience_gained,
            'new_level' => $activeEntry->userSkill->level,
        ];

        if ($levelUpData) {
            $response['level_up'] = $levelUpData;
        }

        return response()->json($response);
    }

    /**
     * Manually log a completed time entry
     */
    public function logManual(Request $request)
    {
        $request->validate([
            'skill_id' => 'required|exists:skills,id',
            'duration_minutes' => 'required|integer|min:1|max:480', // Max 8 hours per entry
            'completed_at' => 'required|date|before_or_equal:now|after:'.now()->subDays(7)->toDateString(),
            'notes' => 'nullable|string|max:500',
        ]);

        $user = $request->user();
        $skill = Skill::findOrFail($request->skill_id);

        // Check daily limit: max 12 hours per skill per day
        $completedAt = \Carbon\Carbon::parse($request->completed_at);
        $dailyMinutes = TimeEntry::where('user_id', $user->id)
            ->where('skill_id', $skill->id)
            ->whereNotNull('ended_at')
            ->whereDate('ended_at', $completedAt->toDateString())
            ->sum('duration_minutes');

        if ($dailyMinutes + $request->duration_minutes > 720) {
            return response()->json([
                'error' => 'Daily limit exceeded. You can only log up to 12 hours per skill per day.',
            ], 422);
        }

        // Get or create UserSkill
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

        // Store level before adding XP
        $levelBefore = $userSkill->level;

        // Create the time entry
        $startedAt = $completedAt->copy()->subMinutes($request->duration_minutes);
        $experienceGained = $request->duration_minutes * $skill->xp_rate;

        $timeEntry = TimeEntry::create([
            'user_id' => $user->id,
            'skill_id' => $skill->id,
            'user_skill_id' => $userSkill->id,
            'started_at' => $startedAt,
            'ended_at' => $completedAt,
            'duration_minutes' => $request->duration_minutes,
            'experience_gained' => $experienceGained,
            'notes' => $request->notes,
        ]);

        // Add XP to user's skill
        $userSkill->addExperience($experienceGained);

        // Check if level increased
        $levelAfter = $userSkill->level;
        $levelUpData = null;

        if ($levelAfter > $levelBefore) {
            // Create achievement
            Achievement::create([
                'user_id' => $user->id,
                'skill_id' => $skill->id,
                'type' => 'level_up',
                'level_reached' => $levelAfter,
                'total_xp' => $userSkill->experience,
                'unlocked_at' => now(),
            ]);

            $levelUpData = [
                'leveled_up' => true,
                'old_level' => $levelBefore,
                'new_level' => $levelAfter,
                'skill' => $skill,
            ];
        }

        $timeEntry->load(['skill', 'userSkill']);

        $response = [
            'time_entry' => $timeEntry,
            'user_skill' => $userSkill,
            'message' => 'Time entry logged successfully',
            'experience_gained' => $experienceGained,
            'new_level' => $levelAfter,
        ];

        if ($levelUpData) {
            $response['level_up'] = $levelUpData;
        }

        return response()->json($response, 201);
    }

    /**
     * Get statistics for a specific skill
     */
    public function skillStats(Request $request, Skill $skill)
    {
        $user = $request->user();

        $userSkill = UserSkill::where('user_id', $user->id)
            ->where('skill_id', $skill->id)
            ->first();

        $totalMinutes = TimeEntry::where('user_id', $user->id)
            ->where('skill_id', $skill->id)
            ->whereNotNull('ended_at')
            ->sum('duration_minutes');

        $totalSessions = TimeEntry::where('user_id', $user->id)
            ->where('skill_id', $skill->id)
            ->whereNotNull('ended_at')
            ->count();

        return response()->json([
            'skill' => $skill,
            'user_skill' => $userSkill,
            'total_minutes' => $totalMinutes,
            'total_hours' => round($totalMinutes / 60, 1),
            'total_sessions' => $totalSessions,
            'average_session_minutes' => $totalSessions > 0 ? round($totalMinutes / $totalSessions, 1) : 0,
        ]);
    }
}
