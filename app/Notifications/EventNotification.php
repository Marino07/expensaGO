<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\LocalEvent;

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
        return (new MailMessage)
            ->subject('Reminder: ' . $this->event->name)
            ->greeting('Hello!')
            ->line('This is a reminder for your upcoming event:')
            ->line('Event: ' . $this->event->name)
            ->line('Date: ' . $this->event->start_date)
            ->line('Location: ' . $this->event->location)
            ->action('View Event Details', url('/events'))
            ->line('Thank you for using ExpensaGO!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
