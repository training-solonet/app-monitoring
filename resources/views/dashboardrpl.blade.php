<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
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

            <!-- Konten Detail -->
            <div id="detail-content" class="container mt-4">
                <h4 class="text-left mb-4 font-weight-bold">Statistik Aktivitas Selama PKL</h4>
                <div class="row g-3">
                    <!-- Card Total Aktivitas Learning -->
                    <div class="col-md-4">
                        <div class="card text-center shadow border-0">
                            <div class="card-header text-white"
                                style="background: linear-gradient(45deg, #ff9f43, #ff6f61);">
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
                            <div class="card-header text-white"
                                style="background: linear-gradient(45deg, #42a5f5, #5c6bc0);">
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
                            <div class="card-header text-white"
                                style="background: linear-gradient(45deg, #66bb6a, #26a69a);">
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
                                aria-valuenow="{{ $persentaseLearning }}" aria-valuemin="0" aria-valuemax="100">
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
                                aria-valuenow="{{ $persentaseProject }}" aria-valuemin="0" aria-valuemax="100">
                                {{ number_format($persentaseProject, 2) }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Konten Diagram -->
            <div class="row mt-4" id="dashboard-content" style="display: none;">
                <div class="col-md-12">
                    <div class="card shadow-sm border h-100">
                        <div class="card-body py-4">
                            <div class="row w-75" style="margin: auto;">
                                <!-- Diagram Lingkaran Learning -->
                                <div class="col-md-6">
                                    <h6 class="text-center mb-3">Persentase Waktu Per Learning</h6>
                                    <div class="chart">
                                        <canvas id="chart-pie-Learning" class="chart-canvas" height="240"></canvas>
                                    </div>
                                    <div class="text-center mt-3">
                                        <button id="toggle-legend-dikantor" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-info-circle me-1"></i> Detail
                                        </button>
                                    </div>
                                </div>
                                <!-- Diagram Lingkaran Project -->
                                <div class="col-md-6">
                                    <h6 class="text-center mb-3">Persentase Waktu Per Project</h6>
                                    <div class="chart">
                                        <canvas id="chart-pie" class="chart-canvas" height="240"></canvas>
                                    </div>
                                    <div class="text-center mt-3">
                                        <button id="toggle-legend-dikantor" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-info-circle me-1"></i> Detail
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil elemen tombol dan konten
            const showDiagramButton = document.getElementById('show-diagram');
            const showDetailButton = document.getElementById('show-detail');
            const dashboardContent = document.getElementById('dashboard-content');
            const detailContent = document.getElementById('detail-content');

            // Fungsi untuk menampilkan dan menyembunyikan elemen
            function toggleContent(showDiagram) {
                if (showDiagram) {
                    dashboardContent.style.display = 'block'; // Tampilkan diagram
                    detailContent.style.display = 'none'; // Sembunyikan detail
                } else {
                    dashboardContent.style.display = 'none'; // Sembunyikan diagram
                    detailContent.style.display = 'block'; // Tampilkan detail
                }
            }

            // Tambahkan event listener ke tombol
            showDiagramButton.addEventListener('click', () => toggleContent(true));
            showDetailButton.addEventListener('click', () => toggleContent(false));

            // Inisialisasi: tampilkan detail secara default
            toggleContent(false);
        });
    </script>
</x-app-layout>
