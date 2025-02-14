<?php

namespace App\Services;

use App\Models\Trip;
use Carbon\Carbon;

class ExpenseAnalyticsService
{
    public static function analyzeExpenses($trip, $expenses)
    {
        $totalCost = $expenses->sum('amount');
        $daysElapsed = Carbon::parse($trip->start_date)->diffInDays(Carbon::now());
        $averageDaily = $daysElapsed > 0 ? $totalCost / $daysElapsed : 0;

        // Calculate projected total based on current spending
        $projectedTotal = ($averageDaily * $trip->duration);
        $plannedDailyBudget = $trip->budget / $trip->duration;

        // Calculate spending metrics
        $metrics = [
            'spending_ratio' => $averageDaily / $plannedDailyBudget,
            'budget_variance_percentage' => (($averageDaily - $plannedDailyBudget) / $plannedDailyBudget) * 100,
            'projected_overflow' => max(0, $projectedTotal - $trip->budget),
            'days_remaining' => $trip->duration - $daysElapsed,
        ];

        // Generate category analysis
        $categoryAnalysis = self::analyzeCategorySpending($expenses, $totalCost);

        // Generate recommendations
        $recommendations = self::generateRecommendations($metrics, $categoryAnalysis);

        return [
            'metrics' => $metrics,
            'category_analysis' => $categoryAnalysis,
            'recommendations' => $recommendations,
        ];
    }

    private static function analyzeCategorySpending($expenses, $totalCost)
    {
        $categorySpending = $expenses->groupBy('category.name')
            ->map(function ($group) use ($totalCost) {
                $amount = $group->sum('amount');
                return [
                    'amount' => round($amount, 2),
                    'percentage' => round(($amount / $totalCost) * 100, 1),
                    'frequency' => $group->count(),
                    'average_transaction' => round($amount / $group->count(), 2),
                ];
            })->sortByDesc('amount');

        return $categorySpending;
    }

    private static function generateRecommendations($metrics, $categoryAnalysis)
    {
        $recommendations = [];

        if ($metrics['spending_ratio'] > 1.2) {
            $recommendations[] = [
                'message' => "Current spending is " . number_format($metrics['budget_variance_percentage'], 1) . "% above planned budget!"
            ];
        }

        $highestCategory = $categoryAnalysis->first();
        if ($highestCategory['percentage'] > 40) {
            $categoryName = key($categoryAnalysis->toArray());
            $recommendations[] = [
                'message' => "Category {$categoryName} accounts for " . number_format($highestCategory['percentage'], 1) . "% of total expenses"
            ];
            $recommendations[] = self::getCategorySpecificRecommendation($categoryName);
        }

        return $recommendations;
    }

    private static function getCategorySpecificRecommendation($category)
    {
        $recommendations = [
            'Food and Drink' => [
                'message' => 'Consider buying groceries from local markets instead of dining out'
            ],
            'Transport' => [
                'message' => 'Explore public transport options or walking for shorter distances'
            ],
            'Shopping' => [
                'message' => 'Focus on essential purchases and meaningful souvenirs'
            ],
            'Accommodation' => [
                'message' => 'Consider alternative accommodation options or longer stays for better rates'
            ],
            'Entertainment' => [
                'message' => 'Look for free events and cultural activities in the area'
            ],
            'Recreation' => [
                'message' => 'Mix paid activities with free outdoor adventures'
            ],
            'Health' => [
                'message' => 'Check if your insurance covers international healthcare'
            ],
            'Services' => [
                'message' => 'Compare service prices and look for package deals'
            ],
            'Education' => [
                'message' => 'Look for student discounts and free learning opportunities'
            ],
            'Business' => [
                'message' => 'Keep business receipts separate for expense claims'
            ],
            'Sightseeing' => [
                'message' => 'Look for city passes and guided tour package deals'
            ],
            'Communication' => [
                'message' => 'Consider local SIM cards or WiFi alternatives'
            ],
            'Insurance' => [
                'message' => 'Compare travel insurance options for best coverage'
            ],
            'Gifts' => [
                'message' => 'Set a specific budget for souvenirs and gifts'
            ],
            'Technology' => [
                'message' => 'Research local repair options and backup solutions'
            ]
        ];

        return $recommendations[$category] ?? [
            'message' => 'Consider optimizing expenses in this category'
        ];
    }
}
