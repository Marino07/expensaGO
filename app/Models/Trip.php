<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    public function Userpreferences()
    {
        return $this->hasOne(UserPreference::class);
    }
}

