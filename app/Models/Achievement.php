<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Achievement extends Model
{
    protected $fillable = [
        'user_id',
        'skill_id',
        'type',
        'level_reached',
        'total_xp',
        'unlocked_at',
    ];

    protected function casts(): array
    {
        return [
            'level_reached' => 'integer',
            'total_xp' => 'integer',
            'unlocked_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }
}
