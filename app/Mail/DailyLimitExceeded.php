<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class DailyLimitExceeded extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $totalSpentToday;

    public function __construct(User $user, $totalSpentToday)
    {
        $this->user = $user;
        $this->totalSpentToday = $totalSpentToday;
    }

    public function build()
    {
        return $this->view('emails.daily_limit_exceeded')
            ->with([
                'userName' => $this->user->name,
                'totalSpentToday' => $this->totalSpentToday,
            ]);
    }
}
