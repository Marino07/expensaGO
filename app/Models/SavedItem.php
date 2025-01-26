<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedItem extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'api_place_id',
        'place_name',
        'place_address',
        'place_details',
        'event_id'
    ];

    protected $casts = [
        'place_details' => 'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(LocalEvent::class, 'event_id');
    }

    public function isEvent(): bool
    {
        return $this->type === 'event';
    }

    public function isPlace(): bool
    {
        return $this->type === 'place';
    }
}
