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
                                    <h6 class="font-weight-semibold text-lg mb-0">Laporan Harian</h6>
                                    <p class="text-sm">Tambahkan kegiatan laporan harian</p>
                                </div>
                                <div class="ms-auto d-flex">
                                    <button type="button"
                                        class="btn btn-sm btn-dark btn-icon d-flex align-items-center me-2" data-bs-toggle="modal" data-bs-target="#tambahLaporanModal">
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
                            <div class="border-bottom py-3 px-3 d-sm-flex align-items-center">
                                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                    <input type="radio" class="btn-check" name="btnradiotable" id="btnradiotable1"
                                        autocomplete="off" checked>
                                    <label class="btn btn-white px-3 mb-0" for="btnradiotable1">All</label>
                                    <input type="radio" class="btn-check" name="btnradiotable" id="btnradiotable2"
                                        autocomplete="off">
                                    <label class="btn btn-white px-3 mb-0" for="btnradiotable2">Monitored</label>
                                    <input type="radio" class="btn-check" name="btnradiotable" id="btnradiotable3"
                                        autocomplete="off">
                                    <label class="btn btn-white px-3 mb-0" for="btnradiotable3">Unmonitored</label>
                                </div>
                                <div class="input-group w-sm-25 ms-auto">
                                    <span class="input-group-text text-body">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px"
                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z">
                                            </path>
                                        </svg>
                                    </span>
                                    <input type="text" class="form-control" placeholder="Search">
                                </div>
                            </div>
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">No</th>
                                            <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Aktivitas</th>
                                            <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Report</th>
                                            <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Waktu Mulai</th>
                                            <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Waktu Selesai</th>
                                            <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Status</th>
                                            <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Total Waktu</th>
                                            <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($siswa as $index => $item)
                                        <!-- Row Pertama untuk tiap item -->
                                        <tr>
                                            <td class="align-middle text-center" rowspan="2">
                                                <p class="text-sm text-dark font-weight-semibold mb-0">{{ $index + 1 }}</p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <p class="text-sm text-dark font-weight-semibold mb-0">{{ $item->kategori }}</p>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <p class="text-sm text-dark mb-0">{{ $item->report }}</p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-sm font-weight-normal">
                                                    {!! $item->waktu_mulai ? \Carbon\Carbon::parse($item->waktu_mulai)->translatedFormat('d F Y, H:i') : '<em>Belum Dimulai</em>' !!}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-sm font-weight-normal">
                                                    {!! $item->waktu_selesai ? \Carbon\Carbon::parse($item->waktu_selesai)->translatedFormat('d F Y, H:i') : '<em>Belum Berakhir</em>' !!}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                @if($item->status == 'to do')
                                                    <span class="badge badge-sm border border-secondary text-uppercase text-secondary bg-secondary">{{ $item->status }}</span>
                                                @elseif($item->status == 'doing')
                                                    <span class="badge badge-sm border border-info text-uppercase text-info bg-info">{{ $item->status }}</span>
                                                @elseif($item->status == 'done')
                                                    <span class="badge badge-sm border border-success text-uppercase text-success bg-success">{{ $item->status }}</span>
                                                @endif
                                            </td>
                                            
                                            <td class="align-middle text-center" id="total-waktu-{{ $item->id }}">
                                                @if($item->status === 'doing')
                                                    <script>
                                                        startTimer('{{ $item->waktu_mulai }}', 'total-waktu-{{ $item->id }}');
                                                    </script>
                                                @else
                                                    {{ $item->total_waktu ?? '00:00:00' }}
                                                @endif
                                            </td>
                                            <script>
                                                function startTimer(waktuMulai, elementId) {
                                                    const startTime = new Date(waktuMulai).getTime();

                                                    console.log("Waktu mulai:", waktuMulai, "Element ID:", elementId);

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

                                            <td class="align-middle text-center">
                                                <form action="{{ route('siswarpl.toggle', $item->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @if($item->status === 'to do')
                                                        <button type="submit" class="btn btn-sm btn-success mb-0">Mulai</button>
                                                    @elseif($item->status === 'doing')
                                                        <button type="submit" class="btn btn-sm btn-danger mb-0" data-bs-toggle="modal" data-bs-target="#EditTimeModal">Selesai</button>
                                                    @else
                                                        <button type="button" class="btn btn-sm btn-secondary mb-0" disabled>Telah Selesai</button>
                                                    @endif
                                                </form>
                                            </td>
                                        </tr>

                                        <tr>
                                            
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="border-top py-3 px-3 d-flex align-items-center">
                                <p class="font-weight-semibold mb-0 text-dark text-sm">Page 1 of 10</p>
                                <div class="ms-auto">
                                    <button class="btn btn-sm btn-white mb-0">Previous</button>
                                    <button class="btn btn-sm btn-white mb-0">Next</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-app.footer />
        </div>
    </main>

<!-- Modal Tambah -->
<div class="modal fade" id="tambahLaporanModal" tabindex="-1" aria-labelledby="tambahLaporanModalLabel" aria-hidden="true">
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
                        <label for="kategori1" class="form-label">Aktivitas</label>
                        <select class="form-select" id="kategori1" name="kategori1" required>
                            <option selected value="">Pilih Aktivitas</option>
                            <option value="DiKantor">Di Kantor</option>
                            <option value="Keluar Dengan Teknisi">Keluar Dengan Teknisi</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="report1" class="form-label">Report</label>
                        <textarea class="form-control" id="report1" name="report1" rows="2" placeholder="Isi kegiatan..." required></textarea>
                    </div>
                    <hr>

                    <!-- Aktivitas Kedua -->
                    <h6 class="text-dark font-weight-semibold">Laporan 2 (Opsional)</h6>
                    <div class="mb-3">
                        <label for="kategori2" class="form-label">Aktivitas</label>
                        <select class="form-select" id="kategori2" name="kategori2">
                            <option selected value="">Pilih Aktivitas</option>
                            <option value="DiKantor">Di Kantor</option>
                            <option value="Keluar Dengan Teknisi">Keluar Dengan Teknisi</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="report2" class="form-label">Report</label>
                        <textarea class="form-control" id="report2" name="report2" rows="2" placeholder="Isi kegiatan..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" form="formTambahLaporan" class="btn btn-info">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editLaporanModal" tabindex="-1" aria-labelledby="editLaporanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahLaporanModalLabel">Edit Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditLaporan" action="{{ route('siswa.storeMultiple') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <h6 class="text-dark font-weight-semibold">Laporan 1</h6>
                    <div class="mb-3">
                        <label for="kategori1" class="form-label">Kategori</label>
                        <select class="form-select" id="kategori1" name="kategori1" required>
                            <option selected value="">Pilih Kategori</option>
                            <option value="DiKantor">Di Kantor</option>
                            <option value="Keluar Dengan Teknisi">Keluar Dengan Teknisi</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="report1" class="form-label">Report</label>
                        <textarea class="form-control" id="report1" name="report1" rows="2" placeholder="Isi kegiatan..." required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" form="formEditLaporan" class="btn btn-info">Simpan</button>
            </div>
        </div>
    </div>
</div>


</x-app-layout>
