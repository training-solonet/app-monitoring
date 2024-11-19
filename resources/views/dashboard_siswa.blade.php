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
                            <button type="button" class="btn btn-sm btn-outline-primary d-flex align-items-center me-2">
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

            {{-- Content Section --}}
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card shadow-sm border h-100">
                        <div class="card-body py-4">
                            <h5 class="card-title text-uppercase text-center font-weight-bold mb-4">
                                Aktivitas Keluar Dengan Teknisi Bulan Ini
                            </h5>
                            <div class="chart mb-2">
                                <canvas id="chart-pie" class="chart-canvas" height="240"></canvas>
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

        const ctxPie = document.getElementById('chart-pie').getContext('2d');
        const pieChart = new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: @json($aktivitasNames->values()),
                datasets: [{
                    data: @json($siswaData->pluck('totalTime')->values()), 
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(201, 203, 207, 0.6)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(201, 203, 207, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: '#333',
                            font: { size: 14 }
                        }
                    },
                    tooltip: {
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
    </script>
</x-app-layout>