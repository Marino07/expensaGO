<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    protected $fillable = ['user_id', 'preferences', 'has_completed_questionnaire'];

    protected $casts = [
        'preferences' => 'array',
        'has_completed_questionnaire' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
