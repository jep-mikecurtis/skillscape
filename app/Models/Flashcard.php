<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Flashcard extends Model
{
    /** @use HasFactory<\Database\Factories\FlashcardFactory> */
    use HasFactory;

    protected $fillable = [
        'user_skill_id',
        'question',
        'answer',
        'times_studied',
        'times_correct',
        'last_studied_at',
    ];

    protected function casts(): array
    {
        return [
            'times_studied' => 'integer',
            'times_correct' => 'integer',
            'last_studied_at' => 'datetime',
        ];
    }

    public function userSkill(): BelongsTo
    {
        return $this->belongsTo(UserSkill::class);
    }

    /**
     * Calculate accuracy percentage
     */
    public function accuracyPercentage(): int
    {
        if ($this->times_studied === 0) {
            return 0;
        }

        return (int) round(($this->times_correct / $this->times_studied) * 100);
    }

    /**
     * Mark flashcard as studied
     */
    public function markStudied(bool $correct): void
    {
        $this->times_studied++;
        if ($correct) {
            $this->times_correct++;
        }
        $this->last_studied_at = now();
        $this->save();
    }
}
