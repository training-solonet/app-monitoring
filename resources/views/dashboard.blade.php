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
                                <h5 class="text-warning font-weight-bold">
                                    {{ $jumlahDataRPL ?? 'Data tidak tersedia' }}
                                </h5>
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
                                <h5 class="text-info font-weight-bold">
                                    {{ $jumlahDataTKJ ?? 'Data tidak tersedia' }}
                                </h5>
                            </div>
                        </div>
                    </div>

                    <!-- Card Total Waktu -->
                    <div class="col-md-4">
                        <div class="card text-center shadow border-0">
                            <div class="card-header text-white"
                                style="background: linear-gradient(45deg, #66bb6a, #26a69a);">
                                <h6 class="text-white">Total Waktu Semua Siswa</h6>
                            </div>
                            <div class="card-body">
                                <h5 class="text-success font-weight-bold">
                                    @php
                                        if ($totalWaktu ?? 0) {
                                            $totalSeconds = abs($totalWaktu);
                                            $hours = floor($totalSeconds / 3600);
                                            $minutes = floor(($totalSeconds % 3600) / 60);
                                            $seconds = $totalSeconds % 60;
                                            echo "{$hours}h {$minutes}m {$seconds}s";
                                        } else {
                                            echo 'Data tidak tersedia';
                                        }
                                    @endphp
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
                                style="width: {{ $persentaseRPL ?? 0 }}%; background: linear-gradient(90deg, #ff9f43, #ff6f61); height: 20px"
                                aria-valuenow="{{ $persentaseRPL ?? 0 }}" aria-valuemin="0" aria-valuemax="100">
                                {{ isset($persentaseRPL) ? number_format($persentaseRPL, 2) . '%' : 'Data tidak tersedia' }}
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar TKJ -->
                    <div class="mb-4">
                        <small>Aktivitas Siswa TKJ</small>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar" role="progressbar"
                                style="width: {{ $persentaseTKJ ?? 0 }}%; background: linear-gradient(90deg, #42a5f5, #5c6bc0); height: 20px"
                                aria-valuenow="{{ $persentaseTKJ ?? 0 }}" aria-valuemin="0" aria-valuemax="100">
                                {{ isset($persentaseTKJ) ? number_format($persentaseTKJ, 2) . '%' : 'Data tidak tersedia' }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- Content Section --}}
            <div class="row mt-4">
                <div id="diagram-content">
                    <div class="d-flex justify-content-between">
                        <h4>Diagram</h4>
                        <p>
                            <select name="user" class="rounded p-1" id="select_user" onchange="pilih_user()">
                                <option value="" disabled selected>Pilih Nama Siswa</option>
                                @if (!empty($userList) && count($userList) > 0)
                                    @foreach ($userList as $user)
                                        <option value="{{ $user->id }}"
                                            {{ $user_id == $user->id ? 'selected' : '' }}>
                                            {{ $user->username }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="">Data tidak tersedia</option>
                                @endif

                            </select>
                            <a href="https://monitoring.connectis.my.id/dashboardpembimbing"
                                class="btn btn-outline-secondary btn-sm" style="margin-top: 10px">
                                <i class="fa-solid fa-arrows-rotate"></i>
                            </a>
                        </p>
                    </div>


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
                                    </div>

                                    {{-- Bar Chart --}}
                                    <div class="col-md-5">
                                        <h6 class="text-center mb-3">Jumlah Aktivitas Per Kategori Semua Siswa</h6>
                                        <div class="chart" style="margin-top:7rem;">
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

        async function pilih_user() {
            var userId = document.getElementById('select_user').value;
            location.replace("dashboardpembimbing?user_id=" + userId);
        }

        // Gunakan Blade untuk menghindari error jika variabel tidak tersedia
        @if (!isset($activityData) || $activityData->isEmpty())
            const activityData = {};
            const activityLabels = [];
        @else
            const activityData = @json($activityData->toArray());
            const activityLabels = @json(array_keys($activityData->toArray()));
        @endif

        @if (!empty($formattedData) && count($formattedData) > 0)
            const piePercentageData = @json($formattedData);
        @else
            const piePercentageData = {};
        @endif

        if (!document.getElementById('chart-pie')) {
            console.error("Canvas chart-pie tidak ditemukan!");
        }

        function drawPie(piePercentageData, activityData, activityLabels) {
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
                    labels: Object.keys(piePercentageData),
                    datasets: [{
                        data: Object.values(piePercentageData),
                        backgroundColor: gradientColorsPie,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    cutout: '70%',
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    const percentage = tooltipItem.raw.toFixed(2);
                                    return `${tooltipItem.label}: ${percentage}%`;
                                }
                            }
                        },
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });
        }
        window.onload = function() {
            drawPie(piePercentageData, activityData, activityLabels);
        };


        document.getElementById('show-diagram').addEventListener('click', function() {
            document.getElementById('diagram-content').style.display = 'block';
            document.getElementById('detail-content').style.display = 'none';
            drawPie(piePercentageData, activityData, activityLabels);
        });

        document.getElementById('show-detail').addEventListener('click', function() {
            document.getElementById('detail-content').style.display = 'block';
            document.getElementById('diagram-content').style.display = 'none';
        });

        // Bar Chart
        const ctxBar = document.getElementById('chart-bar').getContext('2d');
        const gradientBar = ctxBar.createLinearGradient(0, 0, 0, 400);

        gradientBar.addColorStop(0, 'rgba(54, 162, 235, 1)');
        gradientBar.addColorStop(1, 'rgba(54, 162, 235, 0.4)');

        const barChart = new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: activityLabels,
                datasets: [{
                    label: 'Jumlah Aktivitas',
                    data: Object.values(activityData),
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
    </script>


</x-app-layout>
