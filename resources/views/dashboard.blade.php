<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        {{-- Stylesheet and Chart.js --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        {{-- Navbar --}}
        <x-app.navbar />

        {{-- Main Container --}}
        <div class="container-fluid py-4 px-5">
            {{-- Header Section --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="d-md-flex align-items-center justify-content-between mb-3 mx-2">
                        <div>
                            <h3 class="font-weight-bold mb-0">Hi, {{ auth()->user()->username }}</h3>
                            <p class="text-muted mb-0">Berikut ini data aktivitas anda bulan ini!</p>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-3">

            <div class="row">
                <div class="col-md-12 mt-3">
                    <h4 class="font-weight-bold mb-3">Statistik Siswa</h4>
                </div>
            </div>

            {{-- Content Section --}}
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card shadow-sm border h-100">
                        <div class="card-body py-4">
                            <div class="row">
                                {{-- Pie Chart --}}
                                <div class="col-md-6">
                                    <h6 class="text-center mb-3">Persentase Waktu Per Kategori Semua Siswa</h6>
                                    <div class="chart">
                                        <canvas id="chart-pie" class="chart-canvas" height="140"></canvas>
                                    </div>
                                    {{-- Detail Legend Button --}}
                                    <div class="text-center mt-3">
                                        <button id="toggle-legend" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-info-circle me-1"></i> Detail
                                        </button>
                                    </div>
                                </div>

                                {{-- Bar Chart --}}
                                <div class="col-md-6">
                                    <h6 class="text-center mb-3">Jumlah Aktivitas Per Kategori Semua Siswa</h6>
                                    <div class="chart" style="margin-top:4rem;">
                                        <canvas id="chart-bar" class="chart-canvas" height="240"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <x-app.footer />
        </div>
    </main>

    {{-- Chart.js Script --}}
    <script>
        const pieData = @json($chartData);
        const activityData = @json(array_values($activityData));
        const activityLabels = @json(array_keys($activityData));

        // Pie Chart (Persentase Waktu Per Aktivitas)
        const ctxPie = document.getElementById('chart-pie').getContext('2d');
        const pieChart = new Chart(ctxPie, {
            type: 'doughnut',
            data: pieData,
            options: {
                responsive: true,
                cutout: '70%',
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                const value = tooltipItem.raw;
                                return tooltipItem.label + ': ' + value + '%';
                            }
                        }
                    },
                    legend: {
                        position: 'top',
                    }
                }
            }
        });

        // Bar Chart (Jumlah Aktivitas)
        const ctxBar = document.getElementById('chart-bar').getContext('2d');
        const barChart = new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: activityLabels,
                datasets: [{
                    label: 'Jumlah Aktivitas',
                    data: activityData,
                    backgroundColor: '#3366FF',
                    borderRadius: 5,
                    borderColor: '#0033CC',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#333'
                        }
                    },
                    y: {
                        grid: {
                            borderDash: [5, 5],
                            color: '#ddd'
                        },
                        beginAtZero: true,
                        ticks: {
                            color: '#333'
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        titleColor: '#fff',
                    }
                }
            }
        });

        // Toggle legend visibility
        const toggleLegendButton = document.getElementById('toggle-legend');
        toggleLegendButton.addEventListener('click', () => {
            pieChart.options.plugins.legend.display = !pieChart.options.plugins.legend.display;
            pieChart.update();
        });
    </script>
</x-app-layout>
