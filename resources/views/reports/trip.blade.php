<!DOCTYPE html>
<html>
<head>
    <title>Trip Report</title>
</head>
<body>
    <h1>Trip Report for {{ $trip->name }}</h1>
    <p>Total Cost: {{ $total_cost }}€</p>
    <p>Average Daily Cost: {{ $average_daily }}€</p>
    <p>Largest Category: {{ $largest_category->first()->category }} ({{ $largest_category->sum('amount') }}€)</p>
    <h2>Expenses</h2>
    <ul>
        @foreach ($expenses as $expense)
            <li>{{ $expense->category }}: {{ $expense->amount }}€</li>
        @endforeach
    </ul>
</body>
</html>
