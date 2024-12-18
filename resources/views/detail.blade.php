<x-app-layout>
    <x-app.navbar />
    <div class="container mt-2 justify-content-center" style="max-width: 900px; margin-left:350px">
        <h4 class="text-center mb-2">Statistik</h4>
        <div class="row g-2">
            <!-- Total Materi -->
            <div class="col-4 col-md-4">
                <div class="card text-center shadow-sm border-0">
                    <div class="card-header bg-success text-white py-1">
                        <small>Total Materi</small>
                    </div>
                    <div class="card-body p-1">
                        <h5 class="text-success mb-0">{{ $totalMateri }}</h5>
                    </div>
                </div>
            </div>

            <!-- Total Aktivitas -->
            <div class="col-4 col-md-4">
                <div class="card text-center shadow-sm border-0">
                    <div class="card-header bg-warning text-white py-1">
                        <small>Total Aktivitas</small>
                    </div>
                    <div class="card-body p-1">
                        <h5 class="text-warning mb-0">{{ $totalAktivitas }}</h5>
                    </div>
                </div>
            </div>

            <!-- Total Waktu -->
            <div class="col-4 col-md-4">
                <div class="card text-center shadow-sm border-0">
                    <div class="card-header bg-info text-white py-1">
                        <small>Total Waktu</small>
                    </div>
                    <div class="card-body p-1">
                        <h5 class="text-info mb-0">{{ $totalWaktu }} menit</h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Bars -->
        <div class="mt-2">
            <div class="mb-1">
                <small>Total Materi</small>
                <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $totalMateri }}%;" aria-valuenow="{{ $totalMateri }}" aria-valuemin="0" aria-valuemax="100">
                        <!-- Angka dan persen dihapus -->
                    </div>
                </div>
            </div>
        
            <div class="mb-1">
                <small>Total Aktivitas</small>
                <div class="progress">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $totalAktivitas }}%;" aria-valuenow="{{ $totalAktivitas }}" aria-valuemin="0" aria-valuemax="100">
                        <!-- Angka dan persen dihapus -->
                    </div>
                </div>
            </div>
        
            <div class="mb-1">
                <small>Total Waktu (Menit)</small>
                <div class="progress">
                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ $totalWaktu }}%;" aria-valuenow="{{ $totalWaktu }}" aria-valuemin="0" aria-valuemax="100">
                        <!-- Angka dan persen dihapus -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
