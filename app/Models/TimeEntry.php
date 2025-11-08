<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeEntry extends Model
{
    protected $fillable = [
        'user_id',
        'skill_id',
        'user_skill_id',
        'started_at',
        'ended_at',
        'duration_minutes',
        'experience_gained',
        'notes',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'duration_minutes' => 'integer',
        'experience_gained' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }

    public function userSkill(): BelongsTo
    {
        return $this->belongsTo(UserSkill::class);
    }

    /**
     * Check if the time entry is currently active (not ended)
     */
    public function isActive(): bool
    {
        return $this->ended_at === null;
    }

    /**
     * End the time tracking session and calculate XP
     */
    public function endSession(): void
    {
        if (!$this->isActive()) {
            return;
        }

        $this->ended_at = now();
        $this->duration_minutes = $this->started_at->diffInMinutes($this->ended_at);
        
        // Calculate XP: minutes * skill's XP rate
        $this->experience_gained = $this->duration_minutes * $this->skill->xp_rate;
        
        $this->save();

        // Add XP to user's skill
        $this->userSkill->addExperience($this->experience_gained);
    }
}
