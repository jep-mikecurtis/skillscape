<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserSkill extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'skill_id',
        'experience',
        'level',
    ];

    protected $casts = [
        'experience' => 'integer',
        'level' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }

    public function timeEntries(): HasMany
    {
        return $this->hasMany(TimeEntry::class);
    }

    /**
     * Calculate experience needed for a given level (RuneScape formula)
     */
    public static function experienceForLevel(int $level): int
    {
        if ($level === 1) {
            return 0;
        }

        $experience = 0;
        for ($i = 1; $i < $level; $i++) {
            $experience += floor($i + 300 * pow(2, $i / 7.0));
        }

        return floor($experience / 4);
    }

    /**
     * Calculate level from experience (RuneScape formula)
     */
    public static function levelFromExperience(int $experience): int
    {
        $level = 1;

        for ($i = 2; $i <= 120; $i++) {
            if ($experience >= self::experienceForLevel($i)) {
                $level = $i;
            } else {
                break;
            }
        }

        return $level;
    }

    /**
     * Get experience needed for next level
     */
    public function experienceToNextLevel(): int
    {
        if ($this->level >= 120) {
            return 0;
        }

        $nextLevelXp = self::experienceForLevel($this->level + 1);

        return $nextLevelXp - $this->experience;
    }

    /**
     * Add experience and update level
     */
    public function addExperience(int $amount): void
    {
        $this->experience += $amount;
        $this->level = self::levelFromExperience($this->experience);
        $this->save();
    }
}
