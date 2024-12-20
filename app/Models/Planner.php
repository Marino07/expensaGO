<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Planner extends Model
{
    protected $fillable = [
        'trip_id',
        'user_preference_id',
        'status',
    ];

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    public function userPreference(): BelongsTo
    {
        return $this->belongsTo(UserPreference::class);
    }

    public function plannerDays(): HasMany
    {
        return $this->hasMany(PlannerDay::class);
    }
}
