<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        {{-- Stylesheet and Chart.js --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        {{-- Navbar --}}
        <x-app.navbar />

        {{-- Main Container --}}
        <div class="container-fluid py-4 px-5">
            {{-- Header --}}
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

            {{-- Diagram Section --}}
            <div class="row mt-4">
                <!-- Diagram Lingkaran -->
                <div class="col-md-6">
                    <div class="card shadow-sm border h-100">
                        <div class="card-body py-4">
                            <h6 class="text-center mb-3">Persentase Waktu Per Aktivitas</h6>
                            <div class="chart">
                                <canvas id="chart-pie" class="chart-canvas" height="240"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Diagram Batang -->
                <div class="col-md-6">
                    <div class="card shadow-sm border h-100">
                        <div class="card-body py-4">
                            <h6 class="text-center mb-3">Jumlah Aktivitas</h6>
                            <div class="chart">
                                <canvas id="chart-bar" class="chart-canvas" height="240"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Data Materi Section --}}
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card shadow-sm border h-100">
                        <div class="card-body py-4">
                            <h5 class="text-center mb-4">Materi Learning</h5>
                            @if ($materiRpl->isEmpty())
                                <p class="text-center text-muted">Tidak ada materi yang tersedia.</p>
                            @else
                                <ul class="list-group">
                                    @foreach ($materiRpl as $materi)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>{{ $materi->judul }}</span>
                                            <small class="text-muted">{{ $materi->created_at->format('d M Y') }}</small>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- Chart.js Script --}}
    <script>
        const pieChart = new Chart(document.getElementById('chart-pie').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: @json($siswaDataRpl->keys()->toArray()),
                datasets: [{
                    data: @json($siswaDataRpl->pluck('totalTime')->values()),
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const percentage = @json($siswaDataRpl->pluck('percentage')->values())[context.dataIndex];
                                return `${context.label}: ${percentage.toFixed(2)}%`;
                            }
                        }
                    }
                }
            }
        });

        const barChart = new Chart(document.getElementById('chart-bar').getContext('2d'), {
            type: 'bar',
            data: {
                labels: @json($siswaDataRpl->keys()->toArray()),
                datasets: [{
                    label: 'Jumlah Aktivitas',
                    data: @json($jumlahAktivitasRpl->values()),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
</x-app-layout>
