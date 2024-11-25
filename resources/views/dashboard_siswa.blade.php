<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
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
                        <div class="d-flex">
                            <button type="button" class="btn btn-sm btn-outline-info d-flex align-items-center me-2">
                                <i class="fas fa-chart-pie me-1"></i> Diagram
                            </button>
                            <button type="button" class="btn btn-sm btn-dark d-flex align-items-center">
                                <i class="fas fa-list me-1"></i> Detail
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-3">

            <div class="container mt-4">
    <h4 class="text-left mb-4 font-weight-bold">Statistik Aktivitas Selama PKL</h4>

    <div class="row g-3">
        <!-- Card Total Aktivitas Dikantor -->
        <div class="col-md-4">
            <div class="card text-center shadow border-0">
                <div class="card-header text-white" style="background: linear-gradient(45deg, #ff9f43, #ff6f61);">
                    <h6 class="text-white">Total Aktivitas DiKantor</h6>
                </div>
                <div class="card-body">
                    <h5 class="text-warning font-weight-bold">{{ $jumlahDataDikantor }}</h5>
                </div>
            </div>
        </div>

        <!-- Card Total Aktivitas Dengan Teknisi -->
        <div class="col-md-4">
            <div class="card text-center shadow border-0">
                <div class="card-header text-white" style="background: linear-gradient(45deg, #42a5f5, #5c6bc0);">
                    <h6 class="text-white">Total Aktivitas Dengan Teknisi</h6>
                </div>
                <div class="card-body">
                    <h5 class="text-info font-weight-bold">{{ $jumlahDataTeknisi }}</h5>
                </div>
            </div>
        </div>

        <!-- Card Total Waktu -->
        <div class="col-md-4">
            <div class="card text-center shadow border-0">
                <div class="card-header text-white" style="background: linear-gradient(45deg, #66bb6a, #26a69a);">
                    <h6 class="text-white">Total Jumlah Waktu</h6>
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
        <h6 class="font-weight-bold">Perbandingan Aktivitas DiKantor dan Dengan Teknisi</h6>

        <!-- Progress Bar DiKantor -->
        <div class="mb-4">
            <small>Aktivitas DiKantor</small>
            <div class="progress" style="height: 1.5rem;">
                <div class="progress-bar" role="progressbar" 
                    style="width: {{ $persentaseDikantor }}%; background: linear-gradient(90deg, #ff9f43, #ff6f61);" 
                    aria-valuenow="{{ $persentaseDikantor }}" 
                    aria-valuemin="0" 
                    aria-valuemax="100">
                    {{ number_format($persentaseDikantor, 2) }}%
                </div>
            </div>
        </div>

        <!-- Progress Bar Dengan Teknisi -->
        <div>
            <small>Aktivitas Dengan Teknisi</small>
            <div class="progress" style="height: 1.5rem;">
                <div class="progress-bar" role="progressbar" 
                    style="width: {{ $persentaseTeknisi }}%; background: linear-gradient(90deg, #42a5f5, #5c6bc0);" 
                    aria-valuenow="{{ $persentaseTeknisi }}" 
                    aria-valuemin="0" 
                    aria-valuemax="100">
                    {{ number_format($persentaseTeknisi, 2) }}%
                </div>
            </div>
        </div>
    </div>
</div>


            <div class="row">
                <div class="col-md-12 mt-3">
                    <h4 class="font-weight-bold mb-3">Grafik Persentase Aktivitas Dikantor dan Keluar Dengan Teknisi</h4>
                </div>
            </div>
            
            {{-- Content Section --}}
            <div class="row mt-4">
                <!-- Card 1: Untuk Diagram Lingkaran dan Batang DiKantor -->
                <div class="col-md-6">
                    <div class="card shadow-sm border h-100">
                        <div class="card-body py-4">
                            <div class="row">
                                <!-- Diagram Lingkaran DiKantor -->
                                <div class="col-md-12">
                                    <h6 class="text-center mb-3">Persentase Waktu Per Materi DiKantor</h6>
                                    <div class="chart">
                                        <canvas id="chart-pie-dikantor" class="chart-canvas" height="240"></canvas>
                                    </div>
                                    <div class="text-center mt-3">
                                        <button id="toggle-legend-dikantor" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-info-circle me-1"></i> Detail
                                        </button>
                                    </div>
                                </div>
                                <!-- Diagram Batang DiKantor -->
                                <div class="col-md-12 mt-3">
                                    <h6 class="text-center mb-3">Jumlah Aktivitas DiKantor</h6>
                                    <div class="chart">
                                    <canvas id="chart-bar-dikantor" class="chart-canvas" height="519" width="649" style="margin-top:65px; display: block; box-sizing: border-box; height: 380px; width: 481px;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Untuk Diagram Lingkaran dan Batang Dengan Teknisi -->
                <div class="col-md-6">
                    <div class="card shadow-sm border h-100">
                        <div class="card-body py-4">
                            <div class="row">
                                <!-- Diagram Lingkaran Dengan Teknisi -->
                                <div class="col-md-12">
                                    <h6 class="text-center mb-3">Persentase Waktu Per Aktivitas Dengan Teknisi</h6>
                                    <div class="chart">
                                        <canvas id="chart-pie" class="chart-canvas" height="240"></canvas>
                                    </div>
                                    <div class="text-center mt-3">
                                        <button id="toggle-legend" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-info-circle me-1"></i> Detail
                                        </button>
                                    </div>
                                </div>
                                <!-- Diagram Batang Dengan Teknisi -->
                                <div class="col-md-12 mt-3">
                                    <h6 class="text-center mb-3">Jumlah Aktivitas Dengan Teknisi</h6>
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
    function formatTime(seconds) {
        seconds = Math.abs(seconds);
        const h = Math.floor(seconds / 3600);
        const m = Math.floor((seconds % 3600) / 60);
        const s = seconds % 60;
        return `${h}h ${m}m ${s}s`;
    }

    // Pie Chart
    const ctxPie = document.getElementById('chart-pie').getContext('2d');
    const gradientColorsPie = [];
    const colors = [
        'rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)', 'rgba(255, 159, 64, 1)', 'rgba(153, 102, 255, 1)',
        'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(231, 233, 237, 1)', 'rgba(255, 99, 99, 1)',
        'rgba(255, 159, 159, 1)', 'rgba(75, 255, 192, 1)', 'rgba(192, 75, 255, 1)', 'rgba(86, 255, 255, 1)',
        'rgba(75, 64, 192, 1)', 'rgba(192, 75, 132, 1)', 'rgba(159, 255, 64, 1)', 'rgba(132, 255, 159, 1)',
        'rgba(64, 255, 159, 1)', 'rgba(255, 75, 159, 1)', 'rgba(159, 75, 255, 1)', 'rgba(64, 132, 255, 1)'
    ];

    colors.forEach((color) => {
        const gradient = ctxPie.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, color);
        gradient.addColorStop(1, `${color.replace('1)', '0.2)')}`);
        gradientColorsPie.push(gradient);
    });

    const pieChart = new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: @json($aktivitasNames->values()),
            datasets: [{
                data: @json($siswaData->pluck('totalTime')->values()),
                backgroundColor: gradientColorsPie,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            cutout: '70%',
            animation: { animateRotate: true, animateScale: true, duration: 1500 },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleColor: '#fff',
                    callbacks: {
                        label: function (tooltipItem) {
                            const data = tooltipItem.dataset.data;
                            const currentValue = data[tooltipItem.dataIndex];
                            const percentage = @json($siswaData->pluck('percentage')->values())[tooltipItem.dataIndex];
                            return `${tooltipItem.label}: ${percentage.toFixed(2)}% (${formatTime(currentValue)})`;
                        }
                    }
                }
            }
        }
    });

    // Toggle Legend
    const toggleLegendButton = document.getElementById('toggle-legend');
    toggleLegendButton.addEventListener('click', () => {
        pieChart.options.plugins.legend.display = !pieChart.options.plugins.legend.display;
        pieChart.update();
    });

    // Bar Chart
    const ctxBar = document.getElementById('chart-bar').getContext('2d');
    const gradientBar = ctxBar.createLinearGradient(0, 0, 0, 400);
    gradientBar.addColorStop(0, 'rgba(54, 162, 235, 1)');
    gradientBar.addColorStop(1, 'rgba(54, 162, 235, 0.4)');

    const barChart = new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: @json($aktivitasNames->values()),
            datasets: [{
                label: 'Jumlah Aktivitas',
                data: @json($jumlahAktivitas->values()),
                backgroundColor: gradientBar,
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                borderRadius: 5,
                barPercentage: 0.6
            }]
        },
        options: {
            responsive: true,
            animation: { duration: 1500, easing: 'easeInOutQuad' },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 12, weight: 'bold' } } },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.1)' },
                    ticks: { font: { size: 12, weight: 'bold' }, beginAtZero: true }
                }
            }
        }
    });

    // Pie Chart Dikantor
    // Pie Chart Dikantor
    const ctxPieDikantor = document.getElementById('chart-pie-dikantor').getContext('2d');
    const gradientColorsPieDikantor = [];
    colors.forEach((color) => {
        const gradient = ctxPieDikantor.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, color);
        gradient.addColorStop(1, `${color.replace('1)', '0.2)')}`);
        gradientColorsPieDikantor.push(gradient);
    });

    const pieChartDikantor = new Chart(ctxPieDikantor, {
        type: 'doughnut',
        data: {
            labels: @json($materiNames->values()),
            datasets: [{
                data: @json($siswaDataDikantor->pluck('totalTime')->values()),
                backgroundColor: gradientColorsPieDikantor,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            cutout: '70%',
            animation: { animateRotate: true, animateScale: true, duration: 1500 },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleColor: '#fff',
                    callbacks: {
                        label: function (tooltipItem) {
                            const data = tooltipItem.dataset.data;
                            const currentValue = data[tooltipItem.dataIndex];
                            const percentage = @json($siswaDataDikantor->pluck('percentage')->values())[tooltipItem.dataIndex];
                            return `${tooltipItem.label}: ${percentage.toFixed(2)}% (${formatTime(currentValue)})`;
                        }
                    }
                }
            }
        }
    });

    // Toggle Legend Dikantor
    const toggleLegendDikantorButton = document.getElementById('toggle-legend-dikantor');
    toggleLegendDikantorButton.addEventListener('click', () => {
        pieChartDikantor.options.plugins.legend.display = !pieChartDikantor.options.plugins.legend.display;
        pieChartDikantor.update();
    });

    // Bar Chart Dikantor
    const ctxBarDikantor = document.getElementById('chart-bar-dikantor').getContext('2d');
    const gradientBarDikantor = ctxBarDikantor.createLinearGradient(0, 0, 0, 400);
    gradientBarDikantor.addColorStop(0, 'rgba(255, 159, 64, 1)');
    gradientBarDikantor.addColorStop(1, 'rgba(255, 159, 64, 0.4)');

    const barChartDikantor = new Chart(ctxBarDikantor, {
        type: 'bar',
        data: {
            labels: @json($materiNames->values()),
            datasets: [{
                label: 'Jumlah Aktivitas',
                data: @json($jumlahAktivitasDikantor->values()),
                backgroundColor: gradientBarDikantor,
                borderColor: 'rgba(255, 159, 64, 1)',
                borderWidth: 2,
                borderRadius: 5,
                barPercentage: 0.6
            }]
        },
        options: {
            responsive: true,
            animation: { duration: 1500, easing: 'easeInOutQuad' },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 12, weight: 'bold' } } },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.1)' },
                    ticks: { font: { size: 12, weight: 'bold' }, beginAtZero: true }
                }
            }
        }
    });
</script>

</x-app-layout> 