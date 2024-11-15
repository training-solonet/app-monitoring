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
                                <h5 class="card-title">Kemajuan Siswa</h5>
                                <div class="chart mb-2">
                                    <canvas id="chart-bars" class="chart-canvas" height="240"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-md-0 mb-4">
                        <div class="card shadow-xs border h-100">
                            <div class="card-body py-3">
                                <h5 class="card-title">Kemajuan Siswa</h5>
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
            // Bar Chart: Kemajuan Siswa
            var ctxBar = document.getElementById('chart-bars').getContext('2d');
    var barChart = new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: ['Siswa 1', 'Siswa 2', 'Siswa 3', 'Siswa 4', 'Siswa 5'], // Nama Siswa
            datasets: [{
                label: 'Status Kemajuan Siswa',
                data: [70, 40, 90, 55, 80], // Persentase Kemajuan (misalnya, 70% selesai)
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            },
            responsive: true,
            hover: {
                mode: null // Disable hover effect
            }
        }
    });


            // Pie Chart: Status Kemajuan Siswa
            var ctxPie = document.getElementById('chart-pie').getContext('2d');
    var pieChart = new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: ['Selesai', 'Sedang Berlangsung', 'Belum Mulai'], // Status Kemajuan
            datasets: [{
                data: [50, 30, 20], // Persentase Siswa untuk masing-masing status
                backgroundColor: [
                    'rgba(75, 192, 192, 0.6)',  // Selesai
                    'rgba(255, 206, 86, 0.6)',  // Sedang Berlangsung
                    'rgba(255, 99, 132, 0.6)'   // Belum Mulai
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',  // Selesai
                    'rgba(255, 206, 86, 1)',  // Sedang Berlangsung
                    'rgba(255, 99, 132, 1)'   // Belum Mulai
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
                            let total = data.reduce((acc, value) => acc + value, 0);
                            let currentValue = data[tooltipItem.dataIndex];
                            let percentage = ((currentValue / total) * 100).toFixed(2);
                            return `${tooltipItem.label}: ${percentage}%`;
                        }
                    }
                }
            }
        }
    });

        </script>
    </x-app-layout>
