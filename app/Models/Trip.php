<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trip extends Model
{
    /** @use HasFactory<\Database\Factories\TripFactory> */
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
    public function events()
    {
        return $this->hasMany(LocalEvent::class);
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'trip_category');
    }
    public function userPreference()
    {
        return $this->hasOne(UserPreference::class);
    }
    public function planner()
    {
        return $this->hasOne(Planner::class);
    }
    public function isHalfway()
    {
        if ($this->half_away_report) {
            return false;
        }
        $startDate = Carbon::parse($this->start_date);
        $halfwayPoint = $startDate->addDays($this->duration / 2);
        return Carbon::now()->greaterThanOrEqualTo($halfwayPoint);
    }
}

