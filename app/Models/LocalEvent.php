<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalEvent extends Model
{
    /** @use HasFactory<\Database\Factories\LocalEventFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'location',
        'start_date',
        'end_date',
        'description',
        'type',
        'price',
        'place_id'
    ];
}
