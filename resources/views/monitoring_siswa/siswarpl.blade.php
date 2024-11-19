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
                        <div>
                            <div class="border-bottom py-3 px-3 d-sm-flex align-items-center">
                                <form method="GET" action="{{ route('siswarpl.index') }}" id="filterForm"
                                    class="p-2 border rounded shadow-sm w-100 gap-2 d-flex justify-content-start">
                                    <!-- Status Filter -->
                                    <div class="col-sm-2">
                                        <label for="statusFilter">Status</label>
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

                                    <div class="col-sm-2">
                                        <label for="waktu_mulai" class="form-label">Tanggal Mulai</label>
                                        <input type="date" id="waktu_mulai" name="waktu_mulai" class="form-control"
                                            value="{{ request('waktu_mulai') }}"
                                            onchange="document.getElementById('filterForm').submit();">
                                    </div>

                                    <div class="col-sm-2">
                                        <label for="waktu_selesai" class="form-label">Tanggal Selesai</label>
                                        <input type="date" id="waktu_selesai" name="waktu_selesai"
                                            class="form-control" value="{{ request('waktu_selesai') }}"
                                            onchange="document.getElementById('filterForm').submit();">
                                    </div>

                                    <!-- Kategori Filter -->
                                    <div class="col-sm-2" style="width:100px">
                                        <label for="kategoriFilter" class="form-label fw-bold">Kategori</label>
                                        <select class="form-select" style="width:150px" name="kategori"
                                            id="kategoriFilter" onchange="this.form.submit()">
                                            <option value="" disabled selected>Pilih Kategori</option>
                                            <option value="Learning"
                                                {{ request('kategori') == 'Learning' ? 'selected' : '' }}>
                                                Learning</option>
                                            <option value="Project"
                                                {{ request('kategori') == 'Project' ? 'selected' : '' }}>
                                                Project</option>
                                        </select>
                                    </div>

                                    <!-- Reset Button -->
                                    <div class="col-sm-2 d-flex justify-content-center" style="margin-top:30px">
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="window.location.href='{{ route('siswarpl.index') }}'">Reset</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                            No</th>
                                        <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                            Aktivitas</th>
                                        <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                            Materi</th>
                                        <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                            Report</th>
                                        <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                            Waktu Mulai</th>
                                        <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                            Waktu Selesai</th>
                                        <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                            Status</th>
                                        <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                            Total Waktu</th>
                                        <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                            Bukti</th>
                                        <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                            Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                <tbody>
                                    @foreach ($siswarpl as $index => $item)
                                        <tr>
                                            <td class="align-middle text-center" rowspan="2">
                                                <p class="text-sm text-dark font-weight-semibold mb-0">
                                                    {{ $index + 1 }}</p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <p class="text-sm text-dark font-weight-semibold mb-0">
                                                    {{ $item->kategori }}</p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <p
                                                    class="text-sm text-dark font-weight-semibold mb-0 {{ $item->materirpl ? '' : 'fst-italic' }}">
                                                    {{ $item->materirpl?->materi ?? 'Tidak ada materi' }}
                                                </p>
                                            </td>
                                            <td class="align-middle text-center text-sm font-weight-normal">
                                                <p
                                                    class="text-sm text-secondary mb-0 {{ $item->report ? '' : 'fst-italic' }}">
                                                    {{ $item->report ?? 'Belum ada laporan' }}
                                                </p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-sm font-weight-normal">
                                                    {!! $item->waktu_mulai
                                                        ? \Carbon\Carbon::parse($item->waktu_mulai)->translatedFormat('d F Y, H:i')
                                                        : '<em>Belum Dimulai</em>' !!}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-sm font-weight-normal">
                                                    {!! $item->waktu_selesai
                                                        ? \Carbon\Carbon::parse($item->waktu_selesai)->translatedFormat('d F Y, H:i')
                                                        : '<em>Belum Berakhir</em>' !!}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                @if ($item->status == 'to do')
                                                    <span
                                                        class="badge badge-sm border border-secondary text-uppercase text-secondary bg-secondary">{{ $item->status }}</span>
                                                @elseif($item->status == 'doing')
                                                    <span
                                                        class="badge badge-sm border border-info text-uppercase text-info bg-info">{{ $item->status }}</span>
                                                @elseif($item->status == 'done')
                                                    <span
                                                        class="badge badge-sm border border-success text-uppercase text-success bg-success">{{ $item->status }}</span>
                                                @endif
                                            </td>

                                            <td class="align-middle text-center"
                                                id="total-waktu-{{ $item->id }}">
                                                @if ($item->status === 'doing')
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
                                                <button type="button" class="btn btn-sm btn-info mb-0"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#ViewBuktiModal{{ $item->id }}">
                                                    Lihat Bukti
                                                </button>
                                            </td>
                                            <!-- Modal Lihat Bukti -->
                                            <div class="modal fade" id="ViewBuktiModal{{ $item->id }}"
                                                tabindex="-1"
                                                aria-labelledby="ViewBuktiModalLabel{{ $item->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="ViewBuktiModalLabel{{ $item->id }}">Bukti
                                                                Laporan</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @if ($item->bukti)
                                                                <div class="row">
                                                                    @foreach (explode(',', $item->bukti) as $index => $buktiPath)
                                                                        <div class="col-6 col-md-4 mb-3">
                                                                            <div class="card shadow-sm">
                                                                                <a href="{{ Storage::url($buktiPath) }}"
                                                                                    target="_blank">
                                                                                    <img src="{{ Storage::url($buktiPath) }}"
                                                                                        class="card-img-top"
                                                                                        alt="Bukti"
                                                                                        style="max-height: 200px; object-fit: contain;">
                                                                                </a>
                                                                                <div class="card-body text-center">
                                                                                    <p class="card-text">
                                                                                        <small>Bukti
                                                                                            {{ $index + 1 }}</small>
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <p class="text-center">Tidak ada bukti yang
                                                                    diunggah.</p>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <td class="align-middle text-center">
                                                <form action="{{ route('siswa.toggle', $item->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @if ($item->status === 'to do')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-success mb-0">Mulai</button>
                                                    @elseif($item->status === 'doing')
                                                        <button type="button" class="btn btn-sm btn-danger mb-0"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#EditLaporanModal{{ $item->id }}"
                                                            data-id="{{ $item->id }}"
                                                            data-report="{{ $item->report }}"
                                                            data-waktu_selesai="{{ $item->waktu_selesai }}">
                                                            Selesai
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-sm btn-warning mb-0"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editSiswaModal{{ $item->id }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                    @endif
                                                </form>
                                            </td>
                                        </tr>
                                        <!-- Modal Edit Siswa -->
                                        <div class="modal fade" id="editSiswaModal{{ $item->id }}"
                                            tabindex="-1" aria-labelledby="editSiswaModalLabel{{ $item->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="editSiswaModalLabel{{ $item->id }}">Edit
                                                            Laporan</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('siswarpl.update', $item->id) }}"
                                                        method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="report{{ $item->id }}"
                                                                    class="form-label">Laporan</label>
                                                                <textarea class="form-control" id="report{{ $item->id }}" name="report" rows="3">{{ old('report', $item->report) }}</textarea>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="bukti{{ $item->id }}"
                                                                    class="form-label">Bukti</label>
                                                                <input class="form-control" type="file"
                                                                    id="bukti{{ $item->id }}" name="bukti[]"
                                                                    multiple>
                                                            </div>

                                                            <!-- Menampilkan gambar sebelumnya jika ada -->
                                                            @if ($item->bukti)
                                                                <div class="mb-3">
                                                                    <label class="form-label">Gambar
                                                                        Sebelumnya:</label>
                                                                    <div class="row">
                                                                        @foreach (explode(',', $item->bukti) as $index => $buktiPath)
                                                                            <div class="col-6 col-md-4 mb-2">
                                                                                <img src="{{ Storage::url($buktiPath) }}"
                                                                                    class="img-fluid" alt="Bukti"
                                                                                    style="max-height: 100px; object-fit: contain;">
                                                                                <p class="text-center">
                                                                                    <small>Gambar
                                                                                        {{ $index + 1 }}</small>
                                                                                </p>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Tutup</button>
                                                            <button type="submit"
                                                                class="btn btn-info">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>


                                        <tr></tr>
                                        <!-- Modal Selesai -->
                                        <div class="modal fade" id="EditLaporanModal{{ $item->id }}"
                                            tabindex="-1" aria-labelledby="EditLaporanModalLabel{{ $item->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="EditLaporanModalLabel{{ $item->id }}">Edit
                                                            Laporan</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form id="editLaporanForm{{ $item->id }}"
                                                            action="{{ route('siswa.updateTime', $item->id) }}"
                                                            method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('PUT')

                                                            <!-- Aktivitas Dropdown -->
                                                            <div class="mb-3">
                                                                <label for="aktivitasSelect{{ $item->id }}"
                                                                    class="form-label fw-bold">Pilih
                                                                    Aktivitas</label>
                                                                <select class="form-select"
                                                                    id="aktivitasSelect{{ $item->id }}"
                                                                    name="aktivitas_id" required>
                                                                    <option disabled selected>Pilih Aktivitas
                                                                    </option>
                                                                    @foreach ($aktivitasrpl as $aktivitasItem)
                                                                        <option value="{{ $aktivitasItem->id }}">
                                                                            {{ $aktivitasItem->nama_aktivitas }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <!-- Report Textarea -->
                                                            <div class="mb-3">
                                                                <label for="report{{ $item->id }}"
                                                                    class="form-label fw-bold">Laporan</label>
                                                                <textarea name="report" id="report{{ $item->id }}" class="form-control" placeholder="Masukkan laporan..."
                                                                    rows="3" required>{{ old('report', $item->report ?? '') }}</textarea>
                                                            </div>

                                                            <!-- Waktu Selesai Input -->
                                                            <div class="mb-3">
                                                                <label for="waktu_selesai{{ $item->id }}"
                                                                    class="form-label fw-bold">Waktu
                                                                    Selesai</label>
                                                                <input type="time" class="form-control"
                                                                    id="waktu_selesai{{ $item->id }}"
                                                                    name="waktu_selesai"
                                                                    value="{{ \Carbon\Carbon::parse($item->waktu_selesai)->format('H:i') }}"
                                                                    required>
                                                            </div>

                                                            <!-- Bukti Upload Input -->
                                                            <div class="mb-3">
                                                                <label for="bukti{{ $item->id }}"
                                                                    class="form-label fw-bold">Unggah Bukti
                                                                    (Gambar)</label>
                                                                <input type="file" class="form-control"
                                                                    id="bukti{{ $item->id }}" name="bukti[]"
                                                                    accept="image/*" multiple>
                                                                <small class="form-text text-muted">Kamu bisa
                                                                    mengunggah satu atau lebih gambar.</small>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit"
                                                            form="editLaporanForm{{ $item->id }}"
                                                            class="btn btn-info">Simpan Perubahan</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
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
                    <form id="formTambahLaporan" action="{{ route('siswarpl.storeMultiple') }}" method="POST">
                        @csrf

                        <!-- Aktivitas Pertama -->
                        <h6 class="text-dark font-weight-semibold">Aktivitas 1</h6>
                        <div class="mb-3">
                            <label for="kategori1" class="form-label">Kategori</label>
                            <select class="form-select" id="kategori1" name="kategori1" required
                                onchange="toggleMateriDropdown('kategori1', 'materi1')">
                                <option selected value="">Pilih Kategori</option>
                                <option value="Learning">Learning</option>
                                <option value="Project">Project</option>
                            </select>
                        </div>

                        <div class="mb-3" id="materi1" style="display: none;">
                            <label for="materi1Select" class="form-label">Materi</label>
                            <select class="form-select" id="materi1Select" name="materi1">
                                <option selected value="">Pilih Materi</option>
                                @foreach ($materirpl as $item)
                                    <option value="{{ $item->id }}">{{ $item->materi }}</option>
                                @endforeach
                            </select>
                        </div>

                        <hr>

                        <!-- Aktivitas Kedua (Optional) -->
                        <h6 class="text-dark font-weight-semibold">Aktivitas 2 (Opsional)</h6>
                        <div class="mb-3">
                            <label for="kategori2" class="form-label">Kategori</label>
                            <select class="form-select" id="kategori2" name="kategori2"
                                onchange="toggleMateriDropdown('kategori2', 'materi2')">
                                <option selected value="">Pilih Kategori</option>
                                <option value="Learning">Learning</option>
                                <option value="Project">Project</option>
                            </select>
                        </div>

                        <div class="mb-3" id="materi2" style="display: none;">
                            <label for="materi2Select" class="form-label">Materi</label>
                            <select class="form-select" id="materi2Select" name="materi2">
                                <option selected value="">Pilih Materi</option>
                                @foreach ($materirpl as $item)
                                    <option value="{{ $item->id }}">{{ $item->materi }}</option>
                                @endforeach
                            </select>
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

    <script>
        function toggleMateriDropdown(selectId, materiId) {
            const selectElement = document.getElementById(selectId);
            const materiElement = document.getElementById(materiId);

            if (selectElement.value === 'Learning') {
                materiElement.style.display = 'block';
            } else {
                materiElement.style.display = 'none';
            }
        }

        function toggleMateriDropdown(selectId, materi2Id) {
            const selectElement = document.getElementById(selectId);
            const materiElement = document.getElementById(materi2Id);

            if (selectElement.value === 'Learning') {
                materiElement.style.display = 'block';
            } else {
                materiElement.style.display = 'none';
            }
        }
    </script>






</x-app-layout>
