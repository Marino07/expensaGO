<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\LocalEvent;
use App\Models\User;
use App\Notifications\EventNotification;

class SendEventReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $event;
    protected $user;

    public function __construct(LocalEvent $event, User $user)
    {
        $this->event = $event;
        $this->user = $user;
    }

    public function handle(): void
    {
        $this->user->notify(new EventNotification($this->event));
    }
}
