<?php
namespace App\Services;

use App\Models\Trip;
use App\Models\Expense;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ReportService
{
    public static function sendTripReport(Trip $trip)
    {
        try {
            $expenses = Expense::where('trip_id', $trip->id)->with('category')->get();
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
        $analytics = ExpenseAnalyticsService::analyzeExpenses($trip, $expenses);
        $totalCost = $expenses->sum('amount');

        $data = [
            'trip' => $trip,
            'expenses' => $expenses,
            'total_cost' => $totalCost,
            'average_daily' => $analytics['metrics']['average_daily'], // changed from spending_ratio based calc
            'largest_category' => $analytics['category_analysis']->first(),
            'transport_cost' => $expenses->where('category.name', 'Transport')->sum('amount'),
            'budget_difference' => round($analytics['metrics']['budget_variance_percentage'], 1),
            'expenses_by_category' => $analytics['category_analysis'],
            'recommendations' => $analytics['recommendations'],
            'projected_overflow' => $analytics['metrics']['projected_overflow'],
            'days_remaining' => $analytics['metrics']['days_remaining'],
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
