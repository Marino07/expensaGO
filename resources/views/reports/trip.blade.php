<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #f3f4f6;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #3b82f6;
            color: #ffffff;
            padding: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0 0;
            font-size: 14px;
        }

        .content {
            padding: 20px;
        }

        .trip-info {
            display: flex;
            justify-content: space-between;
            background-color: #f9fafb;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .trip-info div {
            flex: 1;
        }

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #1e40af;
        }

        .metrics {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .metric {
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 6px;
        }

        .metric-label {
            font-size: 14px;
            color: #6b7280;
        }

        .metric-value {
            font-size: 20px;
            font-weight: bold;
            margin-top: 5px;
        }

        .warning {
            background-color: #fee2e2;
            border-left: 4px solid #ef4444;
            padding: 15px;
            border-radius: 6px;
            margin-top: 15px;
            color: #b91c1c;
        }

        .category-list {
            list-style: none;
            padding: 0;
        }

        .category-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 15px;
            background-color: #f9fafb;
            border-radius: 6px;
            margin-bottom: 10px;
        }

        .category-amount {
            font-weight: bold;
        }

        .recommendations {
            background-color: #fffbeb;
            padding: 15px;
            border-radius: 6px;
        }

        .recommendation-item {
            background-color: #ffffff;
            text-color: #68f780;
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 10px;
            border-left: 3px solid #f59e0b;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>ExpensaGO</h1>
            <p>Generated on {{ now()->format('F j, Y') }}</p>
        </div>
        <div class="content">
            <div class="trip-info">
                <div>
                    <strong>Location:</strong> {{ $trip->location }}<br>
                    <strong>Duration:</strong> {{ $trip->duration }} days
                </div>
                <div>
                    <strong>Traveler:</strong> {{ $trip->user->name }}<br>
                    <strong>Budget:</strong> {{ number_format($trip->budget, 2) }}€<br>
                    <strong>Status:</strong> Halfway Report
                </div>
            </div>

            <div class="section">
                <div class="section-title">Financial Overview</div>
                <div class="metrics">
                    <div class="metric">
                        <div class="metric-label">Total Expenses</div>
                        <div class="metric-value">{{ number_format($total_cost, 2) }}€</div>
                    </div>
                    <div class="metric">
                        <div class="metric-label">Daily Average</div>
                        <div class="metric-value">{{ number_format($average_daily, 2) }}€</div>
                    </div>
                    <div class="metric"
                        style="color: {{ $total_cost / $trip->budget > 0.8 ? '#dc2626' : ($total_cost / $trip->budget > 0.6 ? '#d97706' : '#15803d') }}">
                        <div class="metric-label">Budget Utilization</div>
                        <div class="metric-value">{{ number_format(($total_cost / $trip->budget) * 100, 1) }}%</div>
                    </div>
                    <div class="metric">
                        <div class="metric-label">Days Remaining</div>
                        <div class="metric-value">{{ floor($days_remaining) }}</div>
                    </div>
                </div>
                @if ($projected_overflow > 0)
                    <div class="warning">
                        Projected Budget Overflow: {{ number_format($projected_overflow, 2) }}€
                    </div>
                @endif
            </div>

            <!-- Add page break before Category Analysis -->
            <div class="section" style="page-break-before: always;">
                <div class="section-title">Category Analysis</div>
                <ul class="category-list">
                    @foreach ($expenses_by_category as $category => $data)
                        <li class="category-item">
                            <span>{{ $category }}</span>
                            <span class="category-amount">
                                {{ number_format($data['amount'], 2) }}€
                                <span style="color: {{ $data['percentage'] > 30 ? '#dc2626' : '#6b7280' }}">
                                    ({{ number_format($data['percentage'], 1) }}%)
                                </span>
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="section">
                <div class="section-title">Insights & Recommendations</div>
                <div class="recommendations">
                    @foreach ($recommendations as $rec)
                        <div class="recommendation-item">{{ $rec['message'] }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</body>

</html>
