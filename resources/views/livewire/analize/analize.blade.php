<div>
    <x-barapp />
    <div class="bg-primary min-h-screen p-6">
        <div class="max-w-7xl mx-auto">
            {{-- Dashboard Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Progress Chart --}}
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <div x-data="progressCharts()" x-init="initProgress()" class="h-48">
                        <canvas id="progressChart"></canvas>
                    </div>
                </div>

                {{-- Spider Chart --}}
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <div x-data="spiderChart()" x-init="initSpider()" class="h-48">
                        <canvas id="spiderChart"></canvas>
                    </div>
                </div>

                {{-- Line and Bar Charts --}}
                <div class="bg-white p-6 rounded-xl shadow-sm col-span-2">
                    <div class="grid grid-cols-2 gap-6">
                        <div x-data="lineChart()" x-init="initLine()">
                            <canvas id="lineChart"></canvas>
                        </div>
                        <div x-data="barChart()" x-init="initBar()">
                            <canvas id="barChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- New Row with two charts: Curve Chart and Stacked Bar Chart --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                {{-- Curve Chart --}}
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <div x-data="curveChart()" x-init="initCurve()" class="h-48">
                        <canvas id="curveChart"></canvas>
                    </div>
                </div>
                {{-- Stacked Bar Chart --}}
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <div x-data="stackedBarChart()" x-init="initStackedBar()" class="h-48">
                        <canvas id="stackedBarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-footer />

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('progressCharts', () => ({
                    initProgress() {
                        new Chart(document.getElementById('progressChart'), {
                            type: 'doughnut',
                            data: {
                                datasets: [{
                                    data: [79, 21],
                                    backgroundColor: ['#90CAF9', '#E3F2FD']
                                }]
                            },
                            options: {
                                cutout: '80%',
                                responsive: true
                            }
                        });
                    }
                }));

                Alpine.data('spiderChart', () => ({
                    initSpider() {
                        new Chart(document.getElementById('spiderChart'), {
                            type: 'radar',
                            data: {
                                labels: ['A', 'B', 'C', 'D', 'E', 'F'],
                                datasets: [{
                                    label: 'Series 1',
                                    data: [34, 45, 37, 56, 34, 43],
                                    borderColor: '#90CAF9',
                                    backgroundColor: 'rgba(144, 202, 249, 0.2)'
                                }]
                            }
                        });
                    }
                }));

                Alpine.data('lineChart', () => ({
                    initLine() {
                        new Chart(document.getElementById('lineChart'), {
                            type: 'line',
                            data: {
                                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                                datasets: [{
                                    label: 'Trends',
                                    data: [65, 59, 80, 81, 56, 55],
                                    borderColor: '#90CAF9'
                                }]
                            }
                        });
                    }
                }));

                Alpine.data('barChart', () => ({
                    initBar() {
                        new Chart(document.getElementById('barChart'), {
                            type: 'bar',
                            data: {
                                labels: ['A', 'B', 'C', 'D', 'E'],
                                datasets: [{
                                    label: 'Data',
                                    data: [12, 19, 3, 5, 2],
                                    backgroundColor: '#90CAF9'
                                }]
                            }
                        });
                    }
                }));

                // New: Curve Chart using a line chart with smooth curves
                Alpine.data('curveChart', () => ({
                    initCurve() {
                        new Chart(document.getElementById('curveChart'), {
                            type: 'line',
                            data: {
                                labels: ['A', 'B', 'C', 'D', 'E', 'F'],
                                datasets: [{
                                    label: 'Series 1',
                                    data: [34, 45, 37, 56, 34, 43],
                                    borderColor: '#90CAF9',
                                    backgroundColor: 'rgba(144, 202, 249, 0.2)',
                                    tension: 0.4 // makes curves smooth
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    title: {
                                        display: true,
                                        text: 'Curve Chart'
                                    }
                                }
                            }
                        });
                    }
                }));

                // Reintroduced: Stacked Bar Chart
                Alpine.data('stackedBarChart', () => ({
                    initStackedBar() {
                        new Chart(document.getElementById('stackedBarChart'), {
                            type: 'bar',
                            data: {
                                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                                datasets: [{
                                        label: 'Income',
                                        data: [500, 600, 700, 650, 800, 750],
                                        backgroundColor: '#90CAF9'
                                    },
                                    {
                                        label: 'Expenses',
                                        data: [400, 450, 500, 480, 520, 500],
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
            });
        </script>
    @endpush
</div>
