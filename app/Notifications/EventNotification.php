<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\LocalEvent;
use Carbon\Carbon;

class EventNotification extends Notification
{
    use Queueable;

    protected $event;

    public function __construct(LocalEvent $event)
    {
        $this->event = $event;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $formattedDate = Carbon::parse($this->event->start_date)->format('l, F j, Y');

        return (new MailMessage)
            ->view('emails.event-notification', [
                'event' => $this->event,
                'user' => $notifiable,
                'formattedDate' => $formattedDate,
                'actionUrl' => url('/events')
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
