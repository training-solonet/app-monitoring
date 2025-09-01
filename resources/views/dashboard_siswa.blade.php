<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        {{-- Stylesheet and Chart.js --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <style>
            #dashboard-content,
            #detail-content {
                transition: opacity 0.3s ease-in-out;
                opacity: 0;
                visibility: hidden;
            }

            #dashboard-content.show,
            #detail-content.show {
                opacity: 1;
                visibility: visible;
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
                        <div>
                            <h3 class="font-weight-bold mb-0">Hi, {{ auth()->user()->nickname }}</h3>
                            <p class="text-muted mb-0">Berikut ini data aktivitas anda bulan ini!</p>
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
                        Anda memiliki <strong>{{ $belumLapor }}</strong> aktivitas yang <strong>belum dilaporkan</strong>.
                    </div>
                @endif

                {{-- Detail --}}
                <div class="container mt-4" id="detail-content" style="display: none;">
                    <h4>Detail</h4>
                    <div class="row g-3">
                        <!-- Card Total Aktivitas Dikantor -->
                        <div class="col-md-4">
                            <div class="card text-center shadow border-0">
                                <div class="card-header text-white"
                                    style="background: linear-gradient(45deg, #ff9f43, #ff6f61);">
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
                                <div class="card-header text-white"
                                    style="background: linear-gradient(45deg, #42a5f5, #5c6bc0);">
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
                                <div class="card-header text-white"
                                    style="background: linear-gradient(45deg, #66bb6a, #26a69a);">
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
                        <h6 class="font-weight-bold">Perbandingan Aktivitas DiKantor dan Dengan Teknisi</h6>

                        <!-- Progress Bar DiKantor -->
                        <div class="mb-4">
                            <small>Aktivitas DiKantor</small>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{ $persentaseDikantor }}%; background: linear-gradient(90deg, #ff9f43, #ff6f61); height: 20px"
                                    aria-valuenow="{{ $persentaseDikantor }}" aria-valuemin="0" aria-valuemax="100">
                                    {{ number_format($persentaseDikantor, 2) }}%
                                </div>
                            </div>
                        </div>

                        <!-- Progress Bar Dengan Teknisi -->
                        <div>
                            <small>Aktivitas Dengan Teknisi</small>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{ $persentaseTeknisi }}%; background: linear-gradient(90deg, #42a5f5, #5c6bc0); height: 20px"
                                    aria-valuenow="{{ $persentaseTeknisi }}" aria-valuemin="0" aria-valuemax="100">
                                    {{ number_format($persentaseTeknisi, 2) }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Diagram --}}
                <div class="d-flex row mt-4 show" id="dashboard-content" style="display: block;">
                    <h4>Diagram</h4>
                    <div class="col-md-12">
                        <div class="card shadow-sm border h-100">
                            <div class="card-body py-4">
                                <div class="row w-75" style="margin: auto;">
                                    <!-- Diagram Lingkaran DiKantor -->
                                    <div class="col-md-6">
                                        <h6 class="text-center mb-3">Persentase Waktu Per Materi DiKantor</h6>
                                        <div class="chart">
                                            <canvas id="chart-pie-dikantor" class="chart-canvas"
                                                height="240"></canvas>
                                        </div>
                                        <div class="text-center mt-3">
                                            <button id="toggle-legend-dikantor" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-info-circle me-1"></i> Detail
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Diagram Batang DiKantor -->
                                    <div class="col-md-6 mt-1">
                                        <h6 class="text-center mb-3">Jumlah Aktivitas DiKantor</h6>
                                        <div class="chart">
                                            <canvas id="chart-bar-dikantor" class="chart-canvas" height="519"
                                                width="649"
                                                style="margin-top: 4rem; display: block; box-sizing: border-box; height: 380px; width: 481px;"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Untuk Diagram Lingkaran dan Batang Dengan Teknisi -->
                    <div class="col-md-12 mt-3">
                        <div class="card shadow-sm border h-100">
                            <div class="card-body py-4">
                                <div class="row w-75" style="margin: auto">
                                    <!-- Diagram Lingkaran Dengan Teknisi -->
                                    <div class="col-md-6">
                                        <h6 class="text-center mb-3">Persentase Waktu Per Pekerjaan Dengan Teknisi</h6>
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
                                    <div class="col-md-6 mt-1">
                                        <h6 class="text-center mb-3">Jumlah Pekerjaan Dengan Teknisi</h6>
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

        // Pie Chart
        // Menampilkan data dalam bentuk pie chart/diagram Lingkaran untuk aktivitas siswa
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
                                const percentage = @json($siswaData->pluck('percentage')->values())[tooltipItem.dataIndex];
                                return `${tooltipItem.label}: ${percentage.toFixed(2)}% (${formatTime(currentValue)})`;
                            }
                        }
                    }
                }
            }
        });

        // Toggle Legend
        // Menyembunyikan atau menampilkan legenda grafik pie/Diagram Lingkaran.
        const toggleLegendButton = document.getElementById('toggle-legend');
        toggleLegendButton.addEventListener('click', () => {
            pieChart.options.plugins.legend.display = !pieChart.options.plugins.legend.display;
            pieChart.update();
        });

        // Bar Chart
        // Menampilkan jumlah aktivitas dalam bentuk grafik batang (bar chart).
        const ctxBar = document.getElementById('chart-bar').getContext('2d');
        const gradientBar = ctxBar.createLinearGradient(0, 0, 0, 400);
        gradientBar.addColorStop(0, 'rgba(54, 162, 235, 1)');
        gradientBar.addColorStop(1, 'rgba(54, 162, 235, 0.4)');

        const barChart = new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: @json($aktivitasNames->values()),
                datasets: [{
                    label: 'Jumlah Pekerjaan',
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
                animation: {
                    duration: 1500,
                    easing: 'easeInOutQuad'
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            font: {
                                size: 12,
                                weight: 'bold'
                            },
                            beginAtZero: true
                        }
                    }
                }
            }
        });

        // Pie Chart Dikantor
        // data yang ditampilkan berfokus pada materi dikantor berupa diagram Lingkaran.
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
        // data yang ditampilkan berfokus pada aktivitas dikantor berupa diagram batang.
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
                animation: {
                    duration: 1500,
                    easing: 'easeInOutQuad'
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            font: {
                                size: 12,
                                weight: 'bold'
                            },
                            beginAtZero: true
                        }
                    }
                }
            }
        });
        //button untuk menampilkan content diagram 
        document.getElementById('show-dashboard-content').addEventListener('click', function() {
            const dashboardContent = document.getElementById('dashboard-content');
            const detailContent = document.getElementById('detail-content');

            // Tampilkan dashboard content dan sembunyikan detail content
            dashboardContent.style.display = 'block';
            dashboardContent.classList.add('show');
            detailContent.style.display = 'none';
            detailContent.classList.remove('show');
        });

        //button untuk menampilkan content detail 
        document.getElementById('show-detail-content').addEventListener('click', function() {
            const detailContent = document.getElementById('detail-content');
            const dashboardContent = document.getElementById('dashboard-content');

            // Tampilkan detail content dan sembunyikan dashboard content
            detailContent.style.display = 'block';
            detailContent.classList.add('show');
            dashboardContent.style.display = 'none';
            dashboardContent.classList.remove('show');
        });
    </script>

</x-app-layout>
