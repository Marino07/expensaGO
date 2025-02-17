<div>
    <x-barapp />
    <div class="bg-primary min-h-screen p-6">
        <div class="max-w-7xl mx-auto space-y-6">
            @if ($hasExpenses)
                <!-- Charts from application -->
                <div class="bg-white shadow-lg rounded-xl p-4">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-3">Expense Analytics</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Bar Chart -->
                        <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-blue-100 p-3">
                            <canvas id="expenseChart" class="w-full" style="height: 300px;"></canvas>
                        </div>
                        <!-- Doughnut Chart -->
                        <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-blue-100 p-3">
                            <canvas id="expenseChart2" class="w-full" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Additional Charts --}}
            <div class="bg-white shadow-lg rounded-xl p-4">
                <h2 class="text-2xl font-semibold text-gray-900 mb-3">Detailed Insights</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Curve Chart --}}
                    <div class="bg-white shadow-sm rounded-lg p-3 border border-blue-100">
                        <h3 class="text-lg font-semibold mb-2">Expense Trend</h3>
                        <div x-data="curveChart()" x-init="initCurve()" class="h-64">
                            <canvas id="curveChart"></canvas>
                        </div>
                    </div>
                    {{-- Stacked Bar Chart --}}
                    <div class="bg-white shadow-sm rounded-lg p-3 border border-blue-100">
                        <h3 class="text-lg font-semibold mb-2">Transactions Overview</h3>
                        <div x-data="stackedBarChart()" x-init="initStackedBar()" class="h-64">
                            <canvas id="stackedBarChart"></canvas>
                        </div>
                    </div>
                    {{-- Daily vs Average Chart --}}
                    <div class="bg-white shadow-sm rounded-lg p-3 border border-blue-100">
                        <h3 class="text-lg font-semibold mb-2">Daily Expenses vs Average</h3>
                        <div x-data="dailyChart()" x-init="initDaily()" class="h-64">
                            <canvas id="dailyChart"></canvas>
                        </div>
                    </div>
                    {{-- Daily Category Chart --}}
                    <div class="bg-white shadow-sm rounded-lg p-3 border border-blue-100">
                        <h3 class="text-lg font-semibold mb-2">Daily Expenses by Category</h3>
                        <div x-data="dailyCategoryChart()" x-init="initDailyCategory()" class="h-64">
                            <canvas id="dailyCategoryChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-footer />

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('curveChart', () => ({
                    initCurve() {
                        new Chart(document.getElementById('curveChart'), {
                            type: 'line',
                            data: {
                                labels: @json($dailyLabels),
                                datasets: [{
                                    label: 'Trip Expense Trend',
                                    data: @json($dailyExpenses),
                                    borderColor: '#90CAF9',
                                    backgroundColor: 'rgba(144, 202, 249, 0.2)',
                                    tension: 0.4
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'top',
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    },
                                    x: {
                                        ticks: {
                                            autoSkip: true,
                                            maxTicksLimit: 7
                                        }
                                    }
                                }
                            }
                        });
                    }
                }));

                Alpine.data('stackedBarChart', () => ({
                    initStackedBar() {
                        new Chart(document.getElementById('stackedBarChart'), {
                            type: 'bar',
                            data: {
                                labels: @json($dailyLabels),
                                datasets: [{
                                        label: 'Daily Limit',
                                        data: Array(@json($dailyLabels).length).fill(
                                            {{ $Budget / max($trip->duration, 1) }}),
                                        backgroundColor: '#90CAF9'
                                    },
                                    {
                                        label: 'Expenses',
                                        data: @json($dailyExpenses),
                                        backgroundColor: '#E3F2FD'
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    x: {
                                        stacked: true,
                                        beginAtZero: true
                                    },
                                    y: {
                                        stacked: true,
                                        beginAtZero: true
                                    }
                                },
                                plugins: {
                                    title: {
                                        display: true,
                                        text: 'Transactions Overview'
                                    }
                                }
                            }
                        });
                    }
                }));

                Alpine.data('dailyChart', () => ({
                    initDaily() {
                        new Chart(document.getElementById('dailyChart').getContext('2d'), {
                            type: 'line',
                            data: {
                                labels: @json($dailyLabels),
                                datasets: [{
                                        label: 'Daily Expenses',
                                        data: @json($dailyExpenses),
                                        borderColor: '#FF7043',
                                        backgroundColor: 'rgba(255, 112, 67, 0.2)',
                                        tension: 0.4
                                    },
                                    {
                                        label: 'Avg Daily Budget',
                                        data: Array(@json($dailyLabels).length).fill(
                                            {{ $Budget / max($trip->duration, 1) }}),
                                        borderColor: '#66BB6A',
                                        backgroundColor: 'rgba(102, 187, 106, 0.2)',
                                        borderDash: [5, 5],
                                        tension: 0
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    x: {
                                        ticks: {
                                            autoSkip: true,
                                            maxTicksLimit: 7
                                        }
                                    }
                                }
                            }
                        });
                    }
                }));

                Alpine.data('dailyCategoryChart', () => ({
                    initDailyCategory() {
                        const ctx = document.getElementById('dailyCategoryChart').getContext('2d');
                        // Predefined colors - extend as needed
                        const colors = [
                            'rgba(75, 192, 192, 0.8)',
                            'rgba(255, 159, 64, 0.8)',
                            'rgba(255, 205, 86, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(153, 102, 255, 0.8)',
                            'rgba(201, 203, 207, 0.8)'
                        ];
                        // Generate datasets by categories
                        const categoryNames = @json($dailyCategories);
                        const dailyData = @json($dailyCategoryData);
                        const datasets = categoryNames.map((cat, index) => ({
                            label: cat,
                            data: dailyData[cat],
                            backgroundColor: colors[index % colors.length],
                            borderWidth: 1
                        }));

                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: @json($dailyLabels),
                                datasets: datasets
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    x: {
                                        stacked: true,
                                        ticks: {
                                            autoSkip: true,
                                            maxTicksLimit: 10
                                        }
                                    },
                                    y: {
                                        stacked: true,
                                        beginAtZero: true
                                    }
                                },
                                plugins: {
                                    title: {
                                        display: true,
                                        text: 'Daily Expenses Split by Category'
                                    }
                                }
                            }
                        });
                    }
                }));
            });

            document.addEventListener('DOMContentLoaded', function() {
                // Bar Chart with adjusted options for smaller size
                try {
                    var ctx = document.getElementById('expenseChart').getContext('2d');
                    var gradientFill = ctx.createLinearGradient(0, 0, 0, 300);
                    gradientFill.addColorStop(0, 'rgba(75, 192, 192, 0.6)');
                    gradientFill.addColorStop(1, 'rgba(75, 192, 192, 0.1)');

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Budget', 'All Expenses'],
                            datasets: [{
                                label: 'Amount in $',
                                data: [{{ $Budget }}, {{ $AllExpenses }}],
                                backgroundColor: [gradientFill, 'rgba(255, 99, 132, 0.6)'],
                                borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'],
                                borderWidth: 2,
                                borderRadius: 5,
                                borderSkipped: false,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        font: {
                                            size: 11
                                        }
                                    }
                                },
                                x: {
                                    ticks: {
                                        font: {
                                            size: 11
                                        }
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                title: {
                                    display: true,
                                    text: 'Budget vs Expenses',
                                    font: {
                                        size: 16
                                    },
                                    padding: {
                                        top: 5,
                                        bottom: 15
                                    }
                                }
                            }
                        }
                    });
                } catch (error) {
                    console.error('Error initializing bar chart:', error);
                }

                // Doughnut Chart with adjusted options
                try {
                    var ctx2 = document.getElementById('expenseChart2').getContext('2d');
                    new Chart(ctx2, {
                        type: 'doughnut',
                        data: {
                            labels: @json($categoryNames),
                            datasets: [{
                                data: @json($categoryExpenses),
                                backgroundColor: [
                                    'rgba(75, 192, 192, 0.8)',
                                    'rgba(255, 99, 132, 0.8)',
                                    'rgba(54, 162, 235, 0.8)',
                                    'rgba(255, 206, 86, 0.8)',
                                    'rgba(153, 102, 255, 0.8)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'right',
                                    labels: {
                                        font: {
                                            size: 11
                                        }
                                    }
                                },
                                title: {
                                    display: true,
                                    text: 'Expenses by Category',
                                    font: {
                                        size: 16
                                    },
                                    padding: {
                                        top: 5,
                                        bottom: 15
                                    }
                                }
                            }
                        }
                    });
                } catch (error) {
                    console.error('Error initializing doughnut chart:', error);
                }
            });
        </script>
    @endpush
</div>
