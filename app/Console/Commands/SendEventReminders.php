<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LocalEvent;
use App\Models\User;
use App\Jobs\SendEventReminder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendEventReminders extends Command
{
    protected $signature = 'events:send-reminders';
    protected $description = 'Send event reminders 2 days before the event start date';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
            $this->info('Starting command execution...');
            Log::info('SendEventReminders command started');

            $events = LocalEvent::whereBetween('id', [25,30])->get();

            $this->info('Found ' . $events->count() . ' events');
            Log::info('Events query completed', ['count' => $events->count()]);

            if ($events->isEmpty()) {
                $this->warn('No events found');
                return 0;
            }

            foreach ($events as $event) {
                try {
                    $this->processEvent($event);
                } catch (\Exception $e) {
                    $this->error("Error processing event {$event->id}: " . $e->getMessage());
                    Log::error('Event processing failed', [
                        'event_id' => $event->id,
                        'error' => $e->getMessage()
                    ]);
                    continue;
                }
            }

            $this->info('Command completed successfully');
            return 0;

        } catch (\Exception $e) {
            $this->error('Command failed: ' . $e->getMessage());
            Log::error('SendEventReminders command failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }

    protected function processEvent($event)
    {
        $this->info("Processing event: {$event->name}");

        $users = User::all();

        if ($users->isEmpty()) {
            $this->warn('No users found');
            return;
        }

        foreach ($users as $user) {
            try {
                SendEventReminder::dispatch($event, $user);
                $this->info("Reminder dispatched: Event #{$event->id} -> User #{$user->id}");
            } catch (\Exception $e) {
                $this->error("Failed to send reminder to user {$user->id}: " . $e->getMessage());
                Log::error('Reminder dispatch failed', [
                    'event_id' => $event->id,
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}
