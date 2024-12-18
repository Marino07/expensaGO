<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{
    /** @use HasFactory<\Database\Factories\ItineraryFactory> */
    use HasFactory;
    protected $fillable = [
        'trip_id',
        'user_preference_id',
        'date',
        'time_slot',
        'activity',
        'location',
        'notes',
        'duration'
    ];
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
    public function userpreference()
    {
        return $this->belongsTo(UserPreference::class);
    }

}
