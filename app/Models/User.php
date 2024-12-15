<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'plaid_access_token',
        'plaid_item_id',
        'plaid_cursor'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'has_completed_questionnaire' => 'boolean',

        ];
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
    public function lastTrip(){
        return $this->trips()->latest()->first();
    }
    public function Lastexpenses()
    {
        $lastTrip = Trip::where('user_id',auth()->id())->latest()->first();

        if (!$lastTrip) {
            return collect();
        }
        return $lastTrip->expenses;
    }
}
