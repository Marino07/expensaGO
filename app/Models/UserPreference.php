<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    protected $fillable = [
        'trip_id',
        'user_id',
        'preferences',
        'has_completed_questionnaire'
    ];

    protected $casts = [
        'preferences' => 'array',
        'has_completed_questionnaire' => 'boolean'
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
    public function itinerary()
    {
        return $this->hasOne(Itinerary::class);
    }
}
