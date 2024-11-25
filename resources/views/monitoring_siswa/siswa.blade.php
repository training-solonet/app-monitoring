<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />


    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card border shadow-xs mb-4">
                        <div class="card-header border-bottom pb-0">
                            <div class="d-sm-flex align-items-center">
                                <div>
                                    <h6 class="font-weight-semibold text-lg mb-0">Laporan Harian siswa</h6>
                                    <p class="text-sm">Tambahkan kegiatan laporan harian anda</p>
                                </div>
                                <div class="ms-auto d-flex">
                                    <button type="button"
                                        class="btn btn-sm btn-dark btn-icon d-flex align-items-center me-2"
                                        data-bs-toggle="modal" data-bs-target="#tambahLaporanModal">
                                        <span class="btn-inner--icon">
                                            <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 24 24" fill="currentColor" class="d-block me-2">
                                                <path
                                                    d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z" />
                                            </svg>
                                        </span>
                                        <span class="btn-inner--text">Tambah Laporan</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 py-0">
                            <div class="border-bottom col-md-12 py-3 px-3 d-sm-flex align-items-center">
                                <form method="GET" action="{{ route('siswa.index') }}" id="filterForm"
                                    class="p-3 mx-0 border rounded shadow-sm w-100 gap-3 d-flex flex-wrap align-items-start">
                                    <!-- Status Filter -->
                                    <div class="col-12 col-md-2 mb-3">
                                        <label for="statusFilter" class="form-label">Status</label>
                                        <select class="form-select" name="status" id="statusFilter"
                                            onchange="this.form.submit()">
                                            <option value="" disabled selected>Pilih Status</option>
                                            <option value="all"
                                                {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>
                                                All</option>
                                            <option value="to do" {{ request('status') == 'to do' ? 'selected' : '' }}>
                                                To Do</option>
                                            <option value="doing" {{ request('status') == 'doing' ? 'selected' : '' }}>
                                                Doing</option>
                                            <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>
                                                Done</option>
                                        </select>
                                    </div>

                                    <!-- Tanggal Mulai -->
                                    <div class="col-12 col-md-2 mb-3">
                                        <label for="waktu_mulai" class="form-label">Tanggal Mulai</label>
                                        <input type="date" id="waktu_mulai" name="waktu_mulai" class="form-control"
                                            value="{{ request('waktu_mulai') }}" onchange="this.form.submit();">
                                    </div>

                                    <!-- Tanggal Selesai -->
                                    <div class="col-12 col-md-2 mb-3">
                                        <label for="waktu_selesai" class="form-label">Tanggal Selesai</label>
                                        <input type="date" id="waktu_selesai" name="waktu_selesai"
                                            class="form-control" value="{{ request('waktu_selesai') }}"
                                            onchange="this.form.submit();">
                                    </div>

                                    <!-- Kategori Filter -->
                                    <div class="col-2 col-md-3 mb-3">
                                        <label for="kategoriFilter" class="form-label fw-bold">Kategori</label>
                                        <select class="form-select" name="kategori" id="kategoriFilter"
                                            onchange="this.form.submit()">
                                            <option value="" disabled selected>Pilih Kategori</option>
                                            <option value="Dikantor"
                                                {{ request('kategori') == 'Dikantor' ? 'selected' : '' }}>Dikantor
                                            </option>
                                            <option value="Keluar Dengan Teknisi"
                                                {{ request('kategori') == 'Keluar Dengan Teknisi' ? 'selected' : '' }}>
                                                Keluar Dengan Teknisi</option>
                                        </select>
                                    </div>

                                    <!-- Reset Button -->
                                    <div class="col-2 col-md-1 d-flex justify-content-center align-items-end">
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="window.location.href='{{ route('siswa.index') }}'"
                                            style="margin-top: 30px">Reset</button>
                                    </div>
                                </form>


                            </div>
                    </div>
                </div>
            </div>
            <x-app.footer />
        </div>
    </main>

    <!-- Modal Tambah -->
    <div class="modal fade" id="tambahLaporanModal" tabindex="-1" aria-labelledby="tambahLaporanModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahLaporanModalLabel">Tambah Laporan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formTambahLaporan" action="{{ route('siswa.storeMultiple') }}" method="POST">
                        @csrf
                        <!-- Laporan Pertama -->
                        <h6 class="text-dark font-weight-semibold">Laporan 1</h6>
                        <div class="mb-3">
                            <label for="kategori1" class="form-label">Kategori</label>
                            <select class="form-select" id="kategori1" name="kategori1" required
                                onchange="toggleMateriDropdown('kategori1', 'materi1')">
                                <option disabled selected ="">Pilih Kategori</option>
                                <option value="DiKantor">Di Kantor</option>
                                <option value="Keluar Dengan Teknisi">Keluar Dengan Teknisi</option>
                            </select>
                        </div>
                        <div class="mb-3" id="materi1" style="display: none;">
                            <label for="materi1Select" class="form-label">Materi</label>
                            <select class="form-select" id="materi1Select" name="materi_id1">
                                <option selected value="">Pilih Materi</option>
                                @foreach ($materitkj as $item)
                                    <option value="{{ $item->id }}">{{ $item->materi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <hr>

                        <!-- Aktivitas Kedua -->
                        <h6 class="text-dark font-weight-semibold">Laporan 2</h6>
                        <div class="mb-3">
                            <label for="kategori2" class="form-label">Kategori</label>
                            <select class="form-select" id="kategori2" name="kategori2"
                                onchange="toggleMateriDropdown('kategori2', 'materi2')">
                                <option selected value="">Pilih Kategori</option>
                                <option value="DiKantor">Di Kantor</option>
                                <option value="Keluar Dengan Teknisi">Keluar Dengan Teknisi</option>
                            </select>
                        </div>
                        <div class="mb-3" id="materi2" style="display: none;">
                            <label for="materi2Select" class="form-label">Materi</label>
                            <select class="form-select" id="materi2Select" name="materi_id2">
                                <option selected value="">Pilih Materi</option>
                                @foreach ($materitkj as $item)
                                    <option value="{{ $item->id }}">{{ $item->materi }}</option>
                                @endforeach
                            </select>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" form="formTambahLaporan" class="btn btn-info">Simpan</button>
                </div>
            </div>
        </div>
        </form>
    </div>

    <!-- Modal Tambah Laporan Teknisi -->
    <div class="modal fade" id="tambahLaporanTeknisi" tabindex="-1" aria-labelledby="tambahLaporanTeknisiLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahLaporanTeknisiLabel">Tambah Laporan Keluar Dengan Teknisi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="tambahLaporanTeknisitkj" action="{{ route('siswa.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="kategoriSelect" class="form-label">Kategori</label>
                            <select class="form-select" id="kategori" name="kategori" required>
                                <option selected value="Keluar Dengan Teknisi">Keluar Dengan Teknisi</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" form="tambahLaporanTeknisitkj" class="btn btn-info">Simpan</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        function toggleMateriDropdown(kategoriId, materiId) {
            const kategoriSelect = document.getElementById(kategoriId);
            const materiDiv = document.getElementById(materiId);

            if (kategoriSelect.value === "DiKantor") {
                materiDiv.style.display = "block";
            } else {
                materiDiv.style.display = "none";
            }
        }
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        @foreach ($siswa as $item)
            @if ($item->status === 'doing' && $item->waktu_mulai)
                startTimer('{{ $item->waktu_mulai }}', 'total-waktu-{{ $item->id }}');
            @endif
        @endforeach
         document.querySelectorAll('.btn-danger').forEach(button => {
            button.addEventListener('click', function(event) {
                const id = this.getAttribute('data-id');
                const report = this.getAttribute('data-report');
                const waktu_selesai = this.getAttribute('data-waktu_selesai');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Aktivitas ini akan diselesaikan atau tambahkan laporan teknisi.",
                    icon: 'warning',
                    showCancelButton: true,
                    showDenyButton: true,
                    confirmButtonText: 'Ya, Selesaikan!',
                    denyButtonText: 'Keluar Dengan Teknisi',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const modal = new bootstrap.Modal(document.getElementById('EditLaporanModal' + id));
                        modal.show();

                        const modalElement = document.getElementById('EditLaporanModal' + id);
                        modalElement.querySelector('input[name="id"]').value = id;
                        modalElement.querySelector('textarea[name="report"]').value = report;
                        modalElement.querySelector('input[name="waktu_selesai"]').value = waktu_selesai;
                    } else if (result.isDenied) {
                        const teknisiModal = new bootstrap.Modal(document.getElementById('tambahLaporanTeknisi'));
                        teknisiModal.show();
                    }
                });
            });
        });
    });

    function startTimer(waktuMulai, elementId) {
        const startTime = new Date(waktuMulai).getTime();

        console.log("Start timer function called with:", waktuMulai, "for element ID:", elementId);

        function updateTime() {
            const now = new Date().getTime();
            const elapsed = now - startTime;

            const hours = Math.floor((elapsed % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((elapsed % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((elapsed % (1000 * 60)) / 1000);

            document.getElementById(elementId).textContent =
                `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        }

        updateTime();
        setInterval(updateTime, 1000);
    }
</script>


</x-app-layout>