<!DOCTYPE html>
<html>

<head>
    <title>Expense Report</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 30px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .header-left {
            display: flex;
            flex-direction: column;
        }

        .header-right {
            margin-left: 20px;
        }

        .brand {
            color: #2196f3;
            font-size: 24px;
            font-weight: 500;
        }

        .trip-info {
            display: flex;
            justify-content: space-between;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .report-box {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .metric {
            margin: 15px 0;
            padding: 15px;
            border-radius: 6px;
            background: #f8f9fa;
        }

        .metric-highlight {
            background: #fff3e0;
            border-left: 3px solid #ff9800;
            font-weight: 500;
        }

        .warning {
            color: #d32f2f;
            font-weight: bold;
            background: #ffebee;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
            border-left: 4px solid #d32f2f;
        }

        .recommendations {
            background: linear-gradient(to right, #fff3e0, #fffde7);
            padding: 25px;
            border-radius: 8px;
            border-left: 4px solid #ff9800;
            margin-bottom: 30px;
        }

        .recommendation-item {
            background: white;
            padding: 15px;
            margin: 12px 0;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-left: 3px solid #ff9800;
            color: #e65100;
            font-weight: 500;
        }

        .budget-critical {
            color: #d32f2f;
            font-weight: bold;
        }

        .budget-warning {
            color: #f57c00;
            font-weight: bold;
        }

        .budget-good {
            color: #2e7d32;
            font-weight: bold;
        }

        .category-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .category-amount {
            color: #333;
        }

        .category-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .section-title {
            color: #1565c0;
            margin-bottom: 20px;
            font-size: 1.2em;
            font-weight: bold;
            padding-bottom: 10px;
            border-bottom: 2px solid #bbdefb;
        }

        .recommendations .section-title {
            color: #e65100;
            border-bottom-color: #ffe0b2;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="header-content">
            <div class="header-left">
                <div class="brand">ExpensaGO</div>
                <div>Generated on {{ now()->format('F j, Y') }}</div>
            </div>
            
        </div>
    </div>

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

    <div class="report-box">
        <div class="section-title">Financial Overview</div>
        <div class="metric metric-highlight">Total Expenses: {{ number_format($total_cost, 2) }}€</div>
        <div class="metric">Daily Average: {{ number_format($average_daily, 2) }}€</div>
        <div
            class="metric {{ $total_cost / $trip->budget > 0.8 ? 'budget-critical' : ($total_cost / $trip->budget > 0.6 ? 'budget-warning' : 'budget-good') }}">
            Budget Utilization: {{ number_format(($total_cost / $trip->budget) * 100, 1) }}%
        </div>
        <div class="metric">Days Remaining: {{ floor($days_remaining) }}</div>
        @if ($projected_overflow > 0)
            <div class="warning">
                Projected Budget Overflow: {{ number_format($projected_overflow, 2) }}€
            </div>
        @endif
    </div>

    <div class="report-box">
        <div class="section-title">Category Analysis</div>
        <ul class="category-list">
            @foreach ($expenses_by_category as $category => $data)
                <li class="category-item">
                    <span>{{ $category }}</span>
                    <span class="category-amount">
                        {{ number_format($data['amount'], 2) }}€
                        <span style="color: {{ $data['percentage'] > 30 ? '#d32f2f' : '#333' }}">
                            ({{ number_format($data['percentage'], 1) }}%)
                        </span>
                    </span>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="recommendations">
        <div class="section-title">Insights & Recommendations</div>
        @foreach ($recommendations as $rec)
            <div class="recommendation-item">{{ $rec['message'] }}</div>
        @endforeach
    </div>

</body>

</html>
