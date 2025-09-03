<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        {{-- Stylesheet and Chart.js --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <style>
            #dashboard-content,
            #detail-content {
                transition: opacity 0.5s ease, transform 0.5s ease;
                opacity: 0;
                transform: translateY(20px);
                pointer-events: none;
                display: none;
            }

            #dashboard-content.show,
            #detail-content.show {
                opacity: 1;
                transform: translateY(0);
                pointer-events: auto;
                display: block;
            }
        </style>

        {{-- Navbar --}}
        <x-app.navbar />

        {{-- Main Container --}}
        <div class="container-fluid py-4 px-5">
            {{-- Header Section --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="d-md-flex align-items-center justify-content-between mb-3 mx-2">
                        <div class="d-flex">
                            <div class="avatar avatar-xl rounded-circle border border-gray-100 border-4 me-3">
                                @if (Auth::user()->pfp_path)
                                <img src="{{ Storage::url(Auth::user()->pfp_path) }}" alt="profile_image" class="w-100 h-100" id="profileImage" style="object-fit: cover">
                                @else
                                <img src="{{ asset('assets/img/img-8.jpg') }}" alt="profile_image" class="w-100 h-100" id="profileImage" style="object-fit: cover">
                                @endif
                            </div>
                            <div>
                                <h3 class="font-weight-bold mb-0">Hi, {{ auth()->user()->nickname }}</h3>
                                <p class="text-muted mb-0">Berikut ini data aktivitas anda bulan ini!</p>
                            </div>
                        </div>
                        <div class="d-flex">
                            <button id="show-dashboard-content" type="button"
                                class="btn btn-sm btn-outline-info d-flex align-items-center me-2">
                                <i class="fas fa-chart-pie me-1"></i> Diagram
                            </button>
                            <div class="d-flex">
                                <button id="show-detail-content" type="button"
                                    class="btn btn-sm btn-outline-dark d-flex align-items-center me-2">
                                    <i class="fas fa-info-circle me-1"></i> Detail
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-3">

                @if ($belumLapor > 0)
                    <div id="belumLaporAlert" class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Anda memiliki <strong>{{ $belumLapor }}</strong> aktivitas yang <strong>belum dilaporkan</strong>. Silahkan menuju laporan harian.
                    </div>
                @endif

                {{-- Detail --}}
                <div class="container mt-4" id="detail-content">
                    <h4>Detail</h4>
                    <div class="row g-3">
                        <!-- Card Total Aktivitas Belajar -->
                        <div class="col-md-4">
                            <div class="card glass-card text-center shadow border-0">
                                <div class="card-header text-white"
                                    style="background: linear-gradient(45deg, #ff9f43, #ff6f61); border-radius: 16px 16px 0 0;">
                                    <h6 class="text-white">Total Aktivitas Belajar</h6>
                                </div>
                                <div class="card-body">
                                    <h5 class="text-warning font-weight-bold">{{ $jumlahDataBelajar }}</h5>
                                </div>
                            </div>
                        </div>

                        <!-- Card Total Aktivitas Projek -->
                        <div class="col-md-4">
                            <div class="card glass-card text-center shadow border-0">
                                <div class="card-header text-white"
                                    style="background: linear-gradient(45deg, #42a5f5, #5c6bc0); border-radius: 16px 16px 0 0;">
                                    <h6 class="text-white">Total Aktivitas Projek</h6>
                                </div>
                                <div class="card-body">
                                    <h5 class="text-info font-weight-bold">{{ $jumlahDataProjek }}</h5>
                                </div>
                            </div>
                        </div>

                        <!-- Card Total Waktu -->
                        <div class="col-md-4">
                            <div class="card glass-card text-center shadow border-0">
                                <div class="card-header text-white"
                                    style="background: linear-gradient(45deg, #66bb6a, #26a69a); border-radius: 16px 16px 0 0;">
                                    <h6 class="text-white">Total Jumlah Waktu</h6>
                                </div>
                                <div class="card-body">
                                    <h5 class="text-success font-weight-bold">
                                        @php
                                            $totalDetik = abs($totalWaktu);
                                            $jam = floor($totalDetik / 3600);
                                            $menit = floor(($totalDetik % 3600) / 60);
                                            $detik = $totalDetik % 60;
                                        @endphp
                                        {{ $jam }}j {{ $menit }}m {{ $detik }}d
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bars -->
                    <div class="mt-3">
                        <h6 class="font-weight-bold">Perbandingan Aktivitas Belajar dan Projek</h6>

                        <!-- Progress Bar Belajar -->
                        <div class="mb-4">
                            <small>Aktivitas Belajar</small>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar"
                                    style="width: {{ $persentaseBelajar }}%; background: linear-gradient(90deg, #ff9f43, #ff6f61); height: 20px"
                                    aria-valuenow="{{ $persentaseBelajar }}" aria-valuemin="0" aria-valuemax="100">
                                    {{ number_format($persentaseBelajar, 2) }}%
                                </div>
                            </div>
                        </div>

                        <!-- Progress Bar Projek -->
                        <div>
                            <small>Aktivitas Projek</small>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar"
                                    style="width: {{ $persentaseProjek }}%; background: linear-gradient(90deg, #42a5f5, #5c6bc0); height: 20px"
                                    aria-valuenow="{{ $persentaseProjek }}" aria-valuemin="0" aria-valuemax="100">
                                    {{ number_format($persentaseProjek, 2) }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Diagram --}}
                <div class="d-flex row mt-4 show" id="dashboard-content">
                    <h4>Diagram</h4>
                    <div class="col-md-12">
                        <div class="card glass-card shadow-sm border h-100">
                            <div class="card-body py-4">
                                <div class="row w-75" style="margin: auto;">
                                    <!-- Diagram Lingkaran Belajar -->
                                    <div class="col-md-6">
                                        <h6 class="text-center mb-3">Persentase Waktu Per Materi Belajar</h6>
                                        <div class="chart">
                                            <canvas id="chart-pie-Belajar" class="chart-canvas"
                                                height="240"></canvas>
                                        </div>
                                        <div class="text-center mt-3">
                                            <button id="toggle-legend-Belajar" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-info-circle me-1"></i> Detail
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Diagram Batang Belajar -->
                                    <div class="col-md-6 mt-1">
                                        <h6 class="text-center mb-3">Jumlah Aktivitas Belajar</h6>
                                        <div class="chart">
                                            <canvas id="chart-bar-Belajar" class="chart-canvas" height="380"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Diagram Projek -->
                    <div class="col-md-12 mt-3 d-none">
                        <div class="card glass-card shadow-sm border h-100">
                            <div class="card-body py-4">
                                <div class="row w-75" style="margin: auto">
                                    <!-- Diagram Lingkaran Projek -->
                                    <div class="col-md-6">
                                        <div class="chart">
                                            <canvas id="chart-pie" class="chart-canvas" height="240"></canvas>
                                        </div>
                                    </div>
                                    <!-- Diagram Batang Projek -->
                                    <div class="col-md-6 mt-1">
                                        <div class="chart" style="margin-top: 5rem;">
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
        </div>
    </main>

    {{-- Chart.js Script --}}
    <script>
        function formatTime(seconds) {
            seconds = Math.abs(seconds);
            const h = Math.floor(seconds / 3600);
            const m = Math.floor((seconds % 3600) / 60);
            const s = seconds % 60;
            return `${h}h ${m}m ${s}s`;
        }

        // Pie Chart Belajar
        const ctxPieBelajar = document.getElementById('chart-pie-Belajar').getContext('2d');
        const gradientColorsPieBelajar = [];
        const colors = [
            'rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)', 'rgba(255, 159, 64, 1)', 'rgba(153, 102, 255, 1)',
            'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(231, 233, 237, 1)', 'rgba(255, 99, 99, 1)'
        ];
        colors.forEach((color) => {
            const gradient = ctxPieBelajar.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, color);
            gradient.addColorStop(1, `${color.replace('1)', '0.2)')}`);
            gradientColorsPieBelajar.push(gradient);
        });

        const pieChartBelajar = new Chart(ctxPieBelajar, {
            type: 'doughnut',
            data: {
                labels: @json($materiNames->values()),
                datasets: [{
                    data: @json($siswaDataBelajar->pluck('totalTime')->values()),
                    backgroundColor: gradientColorsPieBelajar,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                cutout: '70%',
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 1500
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        titleColor: '#fff',
                        callbacks: {
                            label: function(tooltipItem) {
                                const data = tooltipItem.dataset.data;
                                const currentValue = data[tooltipItem.dataIndex];
                                const percentage = @json($siswaDataBelajar->pluck('percentage')->values())[tooltipItem.dataIndex];
                                return `${tooltipItem.label}: ${percentage.toFixed(2)}% (${formatTime(currentValue)})`;
                            }
                        }
                    }
                }
            }
        });

        // Toggle Legend Belajar
        document.getElementById('toggle-legend-Belajar').addEventListener('click', () => {
            pieChartBelajar.options.plugins.legend.display = !pieChartBelajar.options.plugins.legend.display;
            pieChartBelajar.update();
        });

        // Bar Chart Belajar
        const ctxBarBelajar = document.getElementById('chart-bar-Belajar').getContext('2d');
        const gradientBarBelajar = ctxBarBelajar.createLinearGradient(0, 0, 0, 400);
        gradientBarBelajar.addColorStop(0, 'rgba(255, 159, 64, 1)');
        gradientBarBelajar.addColorStop(1, 'rgba(255, 159, 64, 0.4)');

        new Chart(ctxBarBelajar, {
            type: 'bar',
            data: {
                labels: @json($dataAktivitasBelajar['name'] ?? []),
                datasets: [{
                    label: 'Jumlah Aktivitas',
                    data: @json($dataAktivitasBelajar['jumlah'] ?? []),
                    backgroundColor: gradientBarBelajar,
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 2,
                    borderRadius: 5,
                    barPercentage: 0.6
                }]
            },
            options: {
                responsive: true,
                animation: {
                    duration: 1500,
                    easing: 'easeInOutQuad'
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 12, weight: 'bold' } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.1)' },
                        ticks: { font: { size: 12, weight: 'bold' } }
                    }
                }
            }
        });

        // Toggle buttons
        document.getElementById('show-dashboard-content').addEventListener('click', () => {
            document.getElementById('dashboard-content').classList.add('show');
            document.getElementById('detail-content').classList.remove('show');
        });

        document.getElementById('show-detail-content').addEventListener('click', () => {
            document.getElementById('detail-content').classList.add('show');
            document.getElementById('dashboard-content').classList.remove('show');
        });
    </script>
</x-app-layout>
