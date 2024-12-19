<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">

        <x-app.navbar/>

        <div class="container-fluid py-4 px-5">
            <h3 class="font-weight-bold mb-0">Hi, {{ auth()->user()->username }}</h3>
            <p class="text-muted mb-0">Berikut data aktivitas Anda!</p>
            
            <hr class="my-3">

            <div class="container mt-4">
                <h4 class="text-left mb-4 font-weight-bold">Statistik Aktivitas Selama PKL</h4>

                <div class="row g-3">
                    <!-- Card Total Aktivitas Dikantor -->
                    <div class="col-md-4">
                        <div class="card text-center shadow border-0">
                            <div class="card-header text-white" style="background: linear-gradient(45deg, #ff9f43, #ff6f61);">
                                <h6 class="text-white">Total Aktivitas Learning</h6>
                            </div>
                            <div class="card-body">
                                <h5 class="text-warning font-weight-bold">{{ $jumlahDataLearning }}</h5>
                            </div>
                        </div>
                    </div>

                    <!-- Card Total Aktivitas Dengan Teknisi -->
                    <div class="col-md-4">
                        <div class="card text-center shadow border-0">
                            <div class="card-header text-white" style="background: linear-gradient(45deg, #42a5f5, #5c6bc0);">
                                <h6 class="text-white">Total Aktivitas Project</h6>
                            </div>
                            <div class="card-body">
                                <h5 class="text-info font-weight-bold">{{ $jumlahDataProject }}</h5>
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
                    <h6 class="font-weight-bold">Perbandingan Aktivitas Learning dan Project</h6>

                    <!-- Progress Bar Learning -->
                    <div class="mb-4">
                        <small>Aktivitas Learning</small>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar" role="progressbar" 
                                style="width: {{ $persentaseLearning }}%; background: linear-gradient(90deg, #ff9f43, #ff6f61); height: 20px" 
                                aria-valuenow="{{ $persentaseLearning }}" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                                {{ number_format($persentaseLearning, 2) }}%
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar Project -->
                    <div>
                        <small>Aktivitas Project</small>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar" role="progressbar" 
                                style="width: {{ $persentaseProject }}%; background: linear-gradient(90deg, #42a5f5, #5c6bc0); height: 20px" 
                                aria-valuenow="{{ $persentaseProject }}" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                                {{ number_format($persentaseProject, 2) }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12 mt-5">
                    <h4 class="font-weight-bold mb-3 text-center">Grafik Persentase Aktivitas Dikantor dan Keluar
                        Dengan
                        Teknisi</h4>
                </div>
            </div>
            {{-- Diagram --}}
            <div class="d-flex row mt-4" id="dashboard-content" style="display: none;">
                <!-- Card 1: Untuk Diagram Lingkaran dan Batang DiKantor -->
                <div class="col-md-12">
                    <div class="card shadow-sm border h-100">
                        <div class="card-body py-4">
                            <div class="row w-75" style="margin: auto;">
                                <!-- Diagram Lingkaran DiKantor -->
                                <div class="col-md-6">
                                    <h6 class="text-center mb-3">Persentase Waktu Per Learning</h6>
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
                                    <h6 class="text-center mb-3">Jumlah Aktivitas Project</h6>
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
                                <div class="col-md-6 mt-1">
                                    <h6 class="text-center mb-3">Jumlah Aktivitas Dengan Teknisi</h6>
                                    <div class="chart" style="margin-top: 5rem;">
                                        <canvas id="chart-bar" class="chart-canvas" height="240"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

        {{-- Chart.js Script --}}
        <script>
            // Format time utility
            function formatTime(seconds) {
                seconds = Math.abs(seconds);
                const h = Math.floor(seconds / 3600);
                const m = Math.floor((seconds % 3600) / 60);
                const s = seconds % 60;
                return `${h}h ${m}m ${s}s`;
            }
        
            // Data for Pie Chart
            const ctxPie = document.getElementById('chart-pie').getContext('2d');
            const gradientColorsPie = [];
            const colors = [
                'rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)', 'rgba(255, 159, 64, 1)', 'rgba(153, 102, 255, 1)',
                'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(231, 233, 237, 1)', 'rgba(255, 99, 99, 1)',
            ];
        
            colors.forEach((color) => {
                const gradient = ctxPie.createLinearGradient(0, 0, 0, 400);
                gradient.addColorStop(0, color);
                gradient.addColorStop(1, color.replace('1)', '0.2)'));
                gradientColorsPie.push(gradient);
            });
        
            const pieChart = new Chart(ctxPie, {
                type: 'doughnut',
                data: {
                    labels: @json($aktivitasNames),
                    datasets: [{
                        data: @json($siswaData->pluck('totalTime')),
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
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    const data = tooltipItem.dataset.data;
                                    const currentValue = data[tooltipItem.dataIndex];
                                    return `${tooltipItem.label}: (${formatTime(currentValue)})`;
                                }
                            }
                        }
                    }
                }
            });
        
            // Bar Chart
            const ctxBar = document.getElementById('chart-bar').getContext('2d');
            const gradientBar = ctxBar.createLinearGradient(0, 0, 0, 400);
            gradientBar.addColorStop(0, 'rgba(54, 162, 235, 1)');
            gradientBar.addColorStop(1, 'rgba(54, 162, 235, 0.4)');
        
            const barChart = new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: @json($aktivitasNames),
                    datasets: [{
                        label: 'Total Aktivitas',
                        data: @json($siswaData->pluck('totalTime')),
                        backgroundColor: gradientBar,
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
        
    

</x-app-layout>
