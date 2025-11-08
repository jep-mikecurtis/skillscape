<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Skill extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'icon',
        'category',
        'xp_rate',
        'resources',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'xp_rate' => 'integer',
            'is_active' => 'boolean',
            'resources' => 'array',
        ];
    }

    public function userSkills(): HasMany
    {
        return $this->hasMany(UserSkill::class);
    }

    public function timeEntries(): HasMany
    {
        return $this->hasMany(TimeEntry::class);
    }
}
