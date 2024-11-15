<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-md-6 mb-md-0 mb-4">
                    <div class="card shadow-xs border h-100">
                        <div class="card-body py-3">
                            <h5 class="card-title"><strong>AKTIVITAS BULAN INI (Keluar Dengan Teknisi)</strong></h5>
                            <div class="chart mb-2">
                                <canvas id="chart-pie" class="chart-canvas" height="240"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-app.footer />
        </div>
    </main>

    <script>
        // Pie Chart: Persentase Durasi Waktu per Aktivitas untuk kategori "Keluar Dengan Teknisi"
        var ctxPie = document.getElementById('chart-pie').getContext('2d');
        var pieChart = new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: @json($aktivitasNames->values()), // Nama-nama aktivitas
                datasets: [{
                    data: @json($siswaData->pluck('totalTime')->values()), // Total waktu per aktivitas dalam menit
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
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                let data = tooltipItem.dataset.data;
                                let currentValue = data[tooltipItem.dataIndex];
                                let percentage = @json($siswaData->pluck('percentage')->values())[tooltipItem.dataIndex];
                                return `${tooltipItem.label}: ${percentage.toFixed(2)}% (${currentValue} menit)`;
                            }
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>
