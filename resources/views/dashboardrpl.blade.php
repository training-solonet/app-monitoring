<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">

        <x-app.navbar />

        <div class="container-fluid py-4 px-5">
            <h3 class="font-weight-bold mb-0">Hi, {{ auth()->user()->username }}</h3>
            <p class="text-muted mb-0">Berikut data aktivitas Anda!</p>
            
            <hr class="my-3">

            <div class="row mt-4">
                <!-- Diagram Lingkaran -->
                <div class="col-md-6">
                    <canvas id="chart-pie"></canvas>
                </div>

                <!-- Diagram Batang -->
                <div class="col-md-6">
                    <canvas id="chart-bar"></canvas>
                </div>
            </div>
        </div>
    </main>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const pieChart = new Chart(document.getElementById('chart-pie'), {
            type: 'doughnut',
            data: {
                labels: @json($aktivitasNames->values()),
                datasets: [{
                    data: @json($siswaData->pluck('percentage')->values()),
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
                }]
            }
        });

        const barChart = new Chart(document.getElementById('chart-bar'), {
            type: 'bar',
            data: {
                labels: @json($aktivitasNames->values()),
                datasets: [{
                    label: 'Total Waktu',
                    data: @json($siswaData->pluck('totalTime')->values()),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</x-app-layout>
