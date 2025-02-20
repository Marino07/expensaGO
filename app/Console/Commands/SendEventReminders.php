<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Trip;
use App\Models\User;
use App\Models\LocalEvent;
use App\Jobs\SendEventReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendEventReminders extends Command
{
    protected $signature = 'events:send-reminders';
    protected $description = 'Send event reminders for cheap and free events before the event start date';
    public $users;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
            Log::info('Starting event reminders process');
            $this->users = User::all();

            foreach ($this->users as $user) {
                $lastTrip = Trip::where('user_id', $user->id)->orderBy('created_at', 'desc')->first();
                if (!$lastTrip) {
                    Log::warning('No trips found for user', ['user_id' => $user->id]);
                    continue;
                }

                $events = LocalEvent::where('trip_id', $lastTrip->id)
                    ->where(function($query){
                        $query->where('free',1)->orWhere('price', '<', 30);
                    })
                    ->whereDate('start_date', '=', Carbon::now()->addDays(2))
                    ->get();

                foreach($events as $event){
                    $this->processEvent($event, $user);
                }
            }

            Log::info('Event reminders process completed successfully');

        } catch (\Exception $e) {
            Log::error('Event processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->error("Error processing events: " . $e->getMessage());
        }
    }

    public function processEvent($event, $user)
    {
        try {
            SendEventReminder::dispatch($event, $user);
        } catch (\Exception $e) {
            Log::error('Failed to process event', [
                'event_id' => $event->id,
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
