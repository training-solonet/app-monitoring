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
                    <div class="d-md-flex align-items-center justify-content-between mx-2">
                        <div>
                            <h3 class="font-weight-bold mb-0">Hi, {{ auth()->user()->username }}</h3>
                            <p class="text-muted mb-0">Berikut ini data aktivitas semua siswa pkl</p>
                        </div>
                        <div class="d-flex">
                            <button id="show-diagram" type="button"
                                class="btn btn-sm btn-outline-info d-flex align-items-center me-2">
                                <i class="fas fa-chart-pie me-1"></i> Diagram
                            </button>
                            <button id="show-detail" type="button"
                                class="btn btn-sm btn-outline-dark d-flex align-items-center">
                                <i class="fas fa-list-alt me-1"></i> Detail
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-3">

            <div id="detail-content" style="display: none;">
                <h4>Detail</h4>
                <div class="row g-3">
                    <!-- Card Total Aktivitas Siswa RPL -->
                    <div class="col-md-4">
                        <div class="card text-center shadow border-0">
                            <div class="card-header text-white"
                                style="background: linear-gradient(45deg, #ff9f43, #ff6f61);">
                                <h6 class="text-white">Total Aktivitas Siswa RPL</h6>
                            </div>
                            <div class="card-body">
                                <h5 class="text-warning font-weight-bold">{{ $jumlahDataRPL }}</h5>
                            </div>
                        </div>
                    </div>

                    <!-- Card Total Aktivitas Dengan TKJ -->
                    <div class="col-md-4">
                        <div class="card text-center shadow border-0">
                            <div class="card-header text-white"
                                style="background: linear-gradient(45deg, #42a5f5, #5c6bc0);">
                                <h6 class="text-white">Total Aktivitas Siswa TKJ</h6>
                            </div>
                            <div class="card-body">
                                <h5 class="text-info font-weight-bold">{{ $jumlahDataTKJ }}</h5>
                            </div>
                        </div>
                    </div>

                    <!-- Card Total Waktu -->
                    <div class="col-md-4">
                        <div class="card text-center shadow border-0">
                            <div class="card-header text-white"
                                style="background: linear-gradient(45deg, #66bb6a, #26a69a);">
                                <h6 class="text-white">Total Jumlah Waktu Aktivitas Semua Siswa</h6>
                            </div>
                            <div class="card-body">
                                <h5 class="text-success font-weight-bold">
                                    @php
                                        $totalSeconds = abs($totalWaktu);
                                        $hours = floor($totalSeconds / 3600);
                                        $minutes = floor(($totalSeconds % 3600) / 60);
                                        $seconds = $totalSeconds % 60;
                                    @endphp
                                    {{ $hours }}h {{ $minutes }}m {{ $seconds }}s
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Progress Bars -->
                <div class="mt-5">
                    <h6 class="font-weight-bold">Perbandingan Aktivitas Siswa RPL dan Siswa TKJ</h6>

                    <!-- Progress Bar RPL -->
                    <div class="mb-4">
                        <small>Aktivitas Siswa RPL</small>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar" role="progressbar"
                                style="width: {{ $persentaseRPL }}%; background: linear-gradient(90deg, #ff9f43, #ff6f61); height: 20px"
                                aria-valuenow="{{ $persentaseRPL }}" aria-valuemin="0" aria-valuemax="100">
                                {{ number_format($persentaseRPL, 2) }}%
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar TKJ -->
                    <div>
                        <small>Aktivitas Siswa TKJ</small>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar" role="progressbar"
                                style="width: {{ $persentaseTKJ }}%; background: linear-gradient(90deg, #42a5f5, #5c6bc0); height: 20px"
                                aria-valuenow="{{ $persentaseTKJ }}" aria-valuemin="0" aria-valuemax="100">
                                {{ number_format($persentaseTKJ, 2) }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Content Section --}}
            <div class="row mt-4">
                <div id="diagram-content" style="display: none;">
                    <h4>Diagram</h4>
                    <div class="col-md-12">
                        <div class="card shadow-sm border w-auto">
                            <div class="card-body py-4">
                                <div class="row d-flex gap-5 justify-content-center">
                                    {{-- Pie Chart --}}
                                    <div class="col-md-5">
                                        <h6 class="text-center mb-3">Persentase Waktu Per Kategori Semua Siswa</h6>
                                        <div class="chart">
                                            <canvas id="chart-pie" class="chart-canvas" height="100"></canvas>
                                        </div>
                                        {{-- Detail Legend Button --}}
                                        <div class="text-center mt-3">
                                            <button id="toggle-legend" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-info-circle me-1"></i> Detail
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Bar Chart --}}
                                    <div class="col-md-5">
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
        const activityData = @json($activityData->toArray()); // Convert to plain array
        const activityLabels = @json(array_keys($activityData->toArray())); // Convert to plain array and get keys

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

        document.getElementById('show-diagram').addEventListener('click', function() {
            document.getElementById('diagram-content').style.display = 'block';
            document.getElementById('detail-content').style.display = 'none';
        });

        document.getElementById('show-detail').addEventListener('click', function() {
            document.getElementById('detail-content').style.display = 'block';
            document.getElementById('diagram-content').style.display = 'none';
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
