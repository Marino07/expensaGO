<?php
namespace App\Services;

use App\Models\Trip;
use App\Models\Expense;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ReportService
{
    public static function sendTripReport(Trip $trip)
    {
        try {
            $expenses = Expense::where('trip_id', $trip->id)->get();
            Log::info('Expenses retrieved', ['trip_id' => $trip->id, 'expenses_count' => $expenses->count()]);

            $pdf = self::generatePdfReport($trip, $expenses);
            Log::info('PDF generated', ['trip_id' => $trip->id]);

            self::sendEmail($trip->user->email, $pdf);
            Log::info('Email sent', ['email' => $trip->user->email]);
        } catch (\Exception $e) {
            Log::error('Failed to send trip report', ['trip_id' => $trip->id, 'error' => $e->getMessage()]);
        }
    }

    protected static function generatePdfReport(Trip $trip, $expenses)
    {
        $totalCost = $expenses->sum('amount');
        $averageDaily = $trip->duration > 0 ? $totalCost / $trip->duration : 0;

        $data = [
            'trip' => $trip,
            'expenses' => $expenses,
            'total_cost' => $totalCost,
            'average_daily' => $averageDaily,
            'largest_category' => $expenses->groupBy('category')->sortByDesc(function ($group) {
                return $group->sum('amount');
            })->first(),
        ];

        $pdf = PDF::loadView('reports.trip', $data);
        return $pdf->output();
    }

    protected static function sendEmail($email, $pdf)
    {
        Mail::send([], [], function ($message) use ($email, $pdf) {
            $message->to($email)
                ->subject('Your Trip Report')
                ->attachData($pdf, 'trip_report.pdf', [
                    'mime' => 'application/pdf',
                ]);
        });
    }
}
