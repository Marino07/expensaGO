<?php

namespace App\Services;

use Carbon\Carbon;

class DynamicPredictiveAnalytics
{
    public static function predictOverflow($trip, $expenses)
    {
        // Group expenses by day (relative to trip start)
        $startDate = Carbon::parse($trip->start_date)->startOfDay();
        $dailyTotals = [];

        foreach ($expenses as $expense) {
            $day = Carbon::parse($expense->created_at)->startOfDay();
            $dayIndex = $startDate->diffInDays($day) + 1;
            if (!isset($dailyTotals[$dayIndex])) {
                $dailyTotals[$dayIndex] = 0;
            }
            $dailyTotals[$dayIndex] += $expense->amount;
        }

        if (empty($dailyTotals)) {
            return 0;
        }

        // Build cumulative sums for each day
        ksort($dailyTotals);
        $cumulative = [];
        $sum = 0;
        foreach ($dailyTotals as $dayIndex => $total) {
            $sum += $total;
            $cumulative[$dayIndex] = $sum;
        }

        // Ensure there are at least two data points for regression.
        $n = count($cumulative);
        if ($n < 2) {
            return max(0, end($cumulative) - $trip->budget);
        }

        // Prepare data for linear regression using daily indices and cumulative values
        $sumX = array_sum(array_keys($cumulative));
        $sumY = array_sum($cumulative);
        $sumXY = 0;
        $sumXX = 0;
        foreach ($cumulative as $x => $y) {
            $sumXY += $x * $y;
            $sumXX += $x * $x;
        }
        $meanX = $sumX / $n;
        $meanY = $sumY / $n;

        // Calculate denominator and check for division by zero
        $denom = $sumXX - $n * $meanX * $meanX;
        if ($denom == 0) {
            return max(0, end($cumulative) - $trip->budget);
        }

        // Calculate slope (m) and intercept (b) for y = m*x + b
        $slope = ($sumXY - $n * $meanX * $meanY) / $denom;
        $intercept = $meanY - $slope * $meanX;

        // Predict cumulative expense at the end of the trip (day = $trip->duration)
        $predictedTotal = $slope * $trip->duration + $intercept;
        $dynamicOverflow = max(0, $predictedTotal - $trip->budget);

        return $dynamicOverflow;
    }
}
