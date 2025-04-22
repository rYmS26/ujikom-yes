@extends('layouts.app')
@section('content')
<div class="container mx-auto mt-10 p-6 rounded-lg">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Total Pelanggan</h2>
            <p class="text-3xl font-bold text-blue-600">{{ number_format($totalPelanggan) }}</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Total Transaksi</h2>
            <p class="text-3xl font-bold text-blue-600">{{ number_format($totalPemakaian) }}</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Total Belum Bayar</h2>
            <p class="text-3xl font-bold text-red-600">Rp {{ number_format($totalBelumBayar, 0, ',', '.') }}</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Total Sudah Bayar</h2>
            <p class="text-3xl font-bold text-green-600">Rp {{ number_format($totalSudahBayar, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Chart Filter Controls -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <div class="flex flex-col md:flex-row justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 md:mb-0">Visualisasi Data</h2>
            <div class="flex flex-wrap gap-4">
                <div>
                    <label for="chartYear" class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                    <select id="chartYear" class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="chartType" class="block text-sm font-medium text-gray-700 mb-1">Tipe Data</label>
                    <select id="chartType" class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="transactions">Transaksi</option>
                        <option value="payments">Pembayaran</option>
                        <option value="usage">Pemakaian</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Monthly Transactions Chart -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Transaksi Bulanan</h3>
            <div class="h-80">
                <canvas id="monthlyTransactionsChart"></canvas>
            </div>
        </div>

        <!-- Payment Status Chart -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Pembayaran</h3>
            <div class="h-80">
                <canvas id="paymentStatusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Customer Data Chart -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Data Pelanggan</h3>
        <div class="h-80">
            <canvas id="customerDataChart"></canvas>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initial chart data
        let monthlyData = {
            transactions: @json($monthlyTransactions ?? []),
            payments: @json($monthlyPayments ?? []),
            usage: @json($monthlyUsage ?? [])
        };

        let paymentStatusData = @json($paymentStatus ?? ['paid' => 0, 'unpaid' => 0]);
        let customerData = @json($customerData ?? []);

        // Chart colors
        const colors = {
            blue: 'rgba(59, 130, 246, 0.8)',
            lightBlue: 'rgba(96, 165, 250, 0.8)',
            red: 'rgba(239, 68, 68, 0.8)',
            green: 'rgba(34, 197, 94, 0.8)',
            yellow: 'rgba(234, 179, 8, 0.8)',
            purple: 'rgba(168, 85, 247, 0.8)',
            indigo: 'rgba(79, 70, 229, 0.8)',
            pink: 'rgba(236, 72, 153, 0.8)',
            gray: 'rgba(107, 114, 128, 0.8)'
        };

        // Month names in Indonesian
        const monthNames = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        // Initialize charts
        let monthlyTransactionsChart = createMonthlyTransactionsChart();
        let paymentStatusChart = createPaymentStatusChart();
        let customerDataChart = createCustomerDataChart();

        // Event listeners for filters
        document.getElementById('chartYear').addEventListener('change', updateCharts);
        document.getElementById('chartType').addEventListener('change', updateCharts);

        // Function to update charts based on filters
        function updateCharts() {
            const year = document.getElementById('chartYear').value;
            const chartType = document.getElementById('chartType').value;

            // Fetch new data based on filters
            fetch(`/api/chart-data?year=${year}&type=${chartType}`)
                .then(response => response.json())
                .then(data => {
                    // Update chart data
                    monthlyData = data.monthlyData;
                    paymentStatusData = data.paymentStatus;
                    customerData = data.customerData;

                    // Update charts
                    updateMonthlyTransactionsChart();
                    updatePaymentStatusChart();
                    updateCustomerDataChart();
                })
                .catch(error => console.error('Error fetching chart data:', error));
        }

        // Create Monthly Transactions Chart
        function createMonthlyTransactionsChart() {
            const ctx = document.getElementById('monthlyTransactionsChart').getContext('2d');
            return new Chart(ctx, {
                type: 'bar',
                data: getMonthlyChartData(),
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('id-ID', {
                                            style: 'currency',
                                            currency: 'IDR',
                                            minimumFractionDigits: 0,
                                            maximumFractionDigits: 0
                                        }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        }
                    }
                }
            });
        }

        // Update Monthly Transactions Chart
        function updateMonthlyTransactionsChart() {
            monthlyTransactionsChart.data = getMonthlyChartData();
            monthlyTransactionsChart.update();
        }

        // Get data for monthly chart based on selected type
        function getMonthlyChartData() {
            const chartType = document.getElementById('chartType').value;
            const data = monthlyData[chartType];

            let datasets = [];
            if (chartType === 'transactions') {
                datasets = [{
                    label: 'Total Transaksi',
                    data: data.map(item => item.total),
                    backgroundColor: colors.blue
                }];
            } else if (chartType === 'payments') {
                datasets = [
                    {
                        label: 'Sudah Bayar',
                        data: data.map(item => item.paid),
                        backgroundColor: colors.green
                    },
                    {
                        label: 'Belum Bayar',
                        data: data.map(item => item.unpaid),
                        backgroundColor: colors.red
                    }
                ];
            } else if (chartType === 'usage') {
                datasets = [{
                    label: 'Total Pemakaian (kWh)',
                    data: data.map(item => item.usage),
                    backgroundColor: colors.purple
                }];
            }

            return {
                labels: monthNames,
                datasets: datasets
            };
        }

        // Create Payment Status Chart
        function createPaymentStatusChart() {
            const ctx = document.getElementById('paymentStatusChart').getContext('2d');
            return new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Sudah Bayar', 'Belum Bayar'],
                    datasets: [{
                        data: [paymentStatusData.paid, paymentStatusData.unpaid],
                        backgroundColor: [colors.green, colors.red],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return `${label}: Rp ${new Intl.NumberFormat('id-ID').format(value)} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Update Payment Status Chart
        function updatePaymentStatusChart() {
            paymentStatusChart.data.datasets[0].data = [
                paymentStatusData.paid,
                paymentStatusData.unpaid
            ];
            paymentStatusChart.update();
        }

        // Create Customer Data Chart
        function createCustomerDataChart() {
            const ctx = document.getElementById('customerDataChart').getContext('2d');
            return new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: customerData.map(item => item.jenis),
                    datasets: [{
                        label: 'Jumlah Pelanggan',
                        data: customerData.map(item => item.count),
                        backgroundColor: Object.values(colors).slice(0, customerData.length),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        }

        // Update Customer Data Chart
        function updateCustomerDataChart() {
            customerDataChart.data.labels = customerData.map(item => item.jenis);
            customerDataChart.data.datasets[0].data = customerData.map(item => item.count);
            customerDataChart.data.datasets[0].backgroundColor = Object.values(colors).slice(0, customerData.length);
            customerDataChart.update();
        }
    });
</script>
@endsection
