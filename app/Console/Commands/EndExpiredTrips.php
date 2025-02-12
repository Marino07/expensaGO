<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Trip;
use Carbon\Carbon;


class EndExpiredTrips extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trips:end-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'End trips that have passed their end date';
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $expiredTrips = Trip::where('status', 'active')
                            ->where('end_date', '<', $now)
                            ->get();

        foreach ($expiredTrips as $trip) {
            $trip->status = 'completed';
            $trip->save();
            $this->info('Ended trip ID: ' . $trip->id);
        }

        $this->info('Expired trips processing completed.');
    }
}
