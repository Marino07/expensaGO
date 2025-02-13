<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Trip;
use App\Services\ReportService;

class SendTripReport extends Command
{
    protected $signature = 'trip:send-report';
    protected $description = 'Send trip report to users';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $trips = Trip::where('status', 'active')->get();
        foreach ($trips as $trip) {
            if ($trip->isHalfway()) {
                ReportService::sendTripReport($trip);
            }
        }
    }
}
