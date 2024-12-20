<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlannerDay extends Model
{
    protected $fillable = [
        'planner_id',
        'day_number',
        'main_attraction',
        'places_to_visit',
        'estimated_costs',
    ];

    protected $casts = [
        'main_attraction' => 'array',
        'places_to_visit' => 'array',
        'estimated_costs' => 'array',
    ];

    public function planner(): BelongsTo
    {
        return $this->belongsTo(Planner::class);
    }
}
