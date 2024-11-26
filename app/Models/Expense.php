<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    /** @use HasFactory<\Database\Factories\ExpenseFactory> */
    use HasFactory;
    protected $fillable = ['trip_id', 'title', 'amount', 'date', 'is_recurring', 'category_id'];
    protected $casts = [
        'date' => 'datetime',
        'is_recurring' => 'boolean'
    ];
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
