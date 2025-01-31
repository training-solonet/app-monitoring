<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
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
                            <div class="border-bottom col-md-12 py-2 px-2 d-sm-flex align-items-center">
                                <form method="GET" action="{{ route('siswa.index') }}" id="filterForm"
                                    class="p-2 border rounded shadow-sm w-100 d-flex flex-wrap gap-2 align-items-start">

                                    <!-- Status Filter -->
                                    <div class="col-12 col-md-3 mb-2">
                                        <label for="statusFilter" class="form-label">Status</label>
                                        <select class="form-select form-select-sm" style="height:40px" name="status"
                                            id="statusFilter" onchange="this.form.submit()">
                                            <option value="" disabled selected>Pilih Status</option>
                                            <option value="all"
                                                {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>
                                                Semua</option>
                                            <option value="Mulai" {{ request('status') == 'Mulai' ? 'selected' : '' }}>
                                                Mulai</option>
                                            <option value="Sedang Berlangsung"
                                                {{ request('status') == 'Sedang Berlangsung' ? 'selected' : '' }}>Sedang
                                                Berlangsung</option>
                                            <option value="Selesai"
                                                {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                        </select>
                                    </div>

                                    <!-- Kategori Filter -->
                                    <div class="col-12 col-md-3 mb-2">
                                        <label for="kategoriFilter" class="form-label">Kategori</label>
                                        <select class="form-select form-select-sm" name="kategori" id="kategoriFilter"
                                            onchange="this.form.submit()" style="height: 40px">
                                            <option value="" disabled selected>Pilih Kategori</option>
                                            <option value="Dikantor"
                                                {{ request('kategori') == 'Dikantor' ? 'selected' : '' }}>Dikantor
                                            </option>
                                            <option value="Keluar Dengan Teknisi"
                                                {{ request('kategori') == 'Keluar Dengan Teknisi' ? 'selected' : '' }}>
                                                Keluar Dengan Teknisi</option>
                                        </select>
                                    </div>

                                    <!-- Tanggal Mulai -->
                                    <div class="col-sm-2">
                                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                                        <input type="date" id="tanggal_mulai" name="tanggal_mulai"
                                            class="form-control" value="{{ request('tanggal_mulai') }}"
                                            onchange="document.getElementById('filterForm').submit();">
                                    </div>

                                    <!-- Tanggal Selesai -->
                                    <div class="col-sm-2">
                                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                                        <input type="date" id="tanggal_selesai" name="tanggal_selesai"
                                            class="form-control" value="{{ request('tanggal_selesai') }}"
                                            onchange="document.getElementById('filterForm').submit();">
                                    </div>


                                    <!-- Reset Button -->
                                    <div class="col-12 col-md-1 mb-2 d-flex justify-content-center align-items-end">
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                            style="margin-top:30px;"
                                            onclick="window.location.href='{{ route('siswa.index') }}'">Reset</button>
                                    </div>
                                </form>
                            </div>

                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th
                                                class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                                No</th>
                                            <th
                                                class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                                Kategori</th>
                                            <th
                                                class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                                Materi</th>
                                            <th
                                                class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                                Pekerjaan diluar</th>
                                            <th
                                                class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                                detail</th>
                                            <th
                                                class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                                Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($siswa as $index => $item)
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
                                                        class="text-sm text-dark font-weight-semibold mb-0 {{ $item->materitkj ? '' : 'fst-italic' }}">
                                                        {{ $item->materitkj?->materi ?? 'Tidak ada materi' }}
                                                    </p>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <p
                                                        class="text-sm text-dark font-weight-semibold mb-0 {{ $item->aktivitas ? '' : 'fst-italic' }}">
                                                        {{ $item->aktivitas?->nama_aktivitas ?? 'Tidak ada Pekerjaan' }}
                                                    </p>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <a class="mb-0" data-bs-toggle="modal"
                                                        data-bs-target="#DetailModal{{ $item->id }}">
                                                        <i class="fa-solid fa-circle-info"></i>
                                                    </a>
                                                </td>

                                                <td class="align-middle text-center">
                                                    <form action="{{ route('siswa.toggle', $item->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @if ($item->status === 'Mulai')
                                                            <button type="submit"
                                                                class="btn btn-sm btn-success mb-0">
                                                                <i class="fa-solid fa-play"
                                                                    style="font-size: 12px"></i>
                                                            </button>
                                                        @elseif($item->status === 'Sedang Berlangsung')
                                                            <button type="button" class="btn btn-sm btn-danger mb-0"
                                                                data-bs-toggle="modal" data-id="{{ $item->id }}"
                                                                data-report="{{ $item->report }}"
                                                                data-waktu_selesai="{{ $item->waktu_selesai }}"
                                                                data-kategori="{{ $item->kategori }}">
                                                                <i class="fa-solid fa-square"
                                                                    style="font-size: 12px"></i>
                                                            </button>
                                                        @else
                                                            <button type="button" class="btn btn-sm btn-warning mb-0"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editSiswaModal{{ $item->id }}">
                                                                <i class="fas fa-edit" style="font-size: 12px"></i>
                                                            </button>
                                                        @endif
                                                    </form>
                                                </td>
                                            </tr>

                                            <!-- Modal Edit Siswa -->
                                            <div class="modal fade" id="editSiswaModal{{ $item->id }}"
                                                tabindex="-1"
                                                aria-labelledby="editSiswaModalLabel{{ $item->id }}"
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
                                                        <form action="{{ route('siswa.update', $item->id) }}"
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
                                                                                        class="img-fluid"
                                                                                        alt="Bukti"
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
                                                tabindex="-1"
                                                aria-labelledby="EditLaporanModalLabel{{ $item->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="EditLaporanModalLabel{{ $item->id }}">
                                                                Selesaikan Pekerjaan</h5>
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
                                                                @if ($item->kategori === 'Keluar Dengan Teknisi')
                                                                    <div class="mb-3">
                                                                        <label
                                                                            for="aktivitasSelect{{ $item->id }}"
                                                                            class="form-label fw-bold">Pilih
                                                                            Pekerjaan</label>
                                                                        <select class="form-select"
                                                                            id="aktivitasSelect{{ $item->id }}"
                                                                            name="aktivitas_id" required>
                                                                            <option disabled selected>Pilih Pekerjaan
                                                                            </option>
                                                                            @foreach ($aktivitas as $aktivitasItem)
                                                                                <option
                                                                                    value="{{ $aktivitasItem->id }}"
                                                                                    required>
                                                                                    {{ $aktivitasItem->nama_aktivitas }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                @endif

                                                                <!-- Report Textarea -->
                                                                <div class="mb-3">
                                                                    <label for="report{{ $item->id }}"
                                                                        class="form-label fw-bold">Laporan</label>
                                                                    <textarea name="report" id="report{{ $item->id }}" class="form-control" placeholder="Masukkan laporan..."
                                                                        rows="3" required>{{ old('report', $item->report ?? '') }}</textarea>
                                                                </div>

                                                                <!-- Waktu Selesai Input -->
                                                                <div class="mb-3 d-none">
                                                                    <label for="waktu_selesai{{ $item->id }}"
                                                                        class="form-label fw-bold">Waktu
                                                                        Selesai</label>
                                                                    <input type="time" class="form-control"
                                                                        id="waktu_selesai{{ $item->id }}"
                                                                        name="waktu_selesai"
                                                                        value="{{ old('waktu_selesai', \Carbon\Carbon::parse($item->waktu_mulai)->format('H:i')) }}"
                                                                        min="{{ \Carbon\Carbon::parse($item->waktu_mulai)->format('H:i') }}">
                                                                </div>

                                                                <!-- Bukti Upload Input -->
                                                                <div class="mb-3">
                                                                    <label for="bukti{{ $item->id }}"
                                                                        class="form-label fw-bold">Unggah Bukti
                                                                        (jpg,png,svg,jpeg,gif)</label>
                                                                    <input type="file" class="form-control"
                                                                        id="bukti{{ $item->id }}" name="bukti[]"
                                                                        accept="image/*" multiple>
                                                                    <small class="form-text text-muted">Anda dapat
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
                        <h6 class="text-dark font-weight-semibold">Silahkan inputkan laporan</h6>
                        <div class="mb-3">
                            <label for="kategori1" class="form-label">Kategori</label>
                            <select class="form-select" id="kategori1" name="kategori1" required
                                onchange="toggleMateriDropdown('kategori1', 'materi1')">
                                <option disabled selected ="" required>Pilih Kategori</option>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" form="formTambahLaporan" class="btn btn-info">Simpan</button>
                </div>
            </div>
        </div>
        </form>
    </div>

    {{-- Modal detail --}}
    @foreach ($siswa as $item)
        <div class="modal fade" id="DetailModal{{ $item->id }}" tabindex="-1"
            aria-labelledby="DetailModalLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content shadow-lg rounded">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title text-white" id="DetailModalLabel{{ $item->id }}">
                            Detail Laporan
                        </h5>
                        <button type="button" class="btn-close text-light" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><strong>Catatan Siswa:</strong>
                                        <textarea class="form-control" rows="4" readonly>{{ $item->report ?? 'Tidak ada catatan' }}</textarea>
                                    </li>
                                    <li class="list-group-item"><strong>Waktu Mulai:</strong>
                                        {{ \Carbon\Carbon::parse($item->waktu_mulai)->locale('id')->translatedFormat('l, d M Y H:i') }}
                                    </li>
                                    <li class="list-group-item"><strong>Waktu Selesai:</strong>
                                        {{ \Carbon\Carbon::parse($item->waktu_selesai)->locale('id')->translatedFormat('l, d M Y H:i') }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Status:</strong>
                                        @php
                                            $statusClass = match ($item->status) {
                                                'Selesai' => 'btn-success',
                                                'Belum Dimulai' => 'btn-secondary',
                                                'Sedang Berlangsung' => 'btn-info',
                                                default => 'btn-dark',
                                            };
                                        @endphp
                                        <button
                                            class="btn {{ $statusClass }} btn-sm mt-3">{{ $item->status }}</button>
                                    </li>
                                    <li class="list-group-item"><strong>Total Waktu:</strong>
                                        @php
                                            $startTime = \Carbon\Carbon::parse($item->waktu_mulai);
                                            $endTime = \Carbon\Carbon::parse($item->waktu_selesai);
                                            $diffInMinutes = $startTime->diffInMinutes($endTime);
                                            $hours = intdiv($diffInMinutes, 60); // Calculate hours
                                            $minutes = $diffInMinutes % 60; // Calculate remaining minutes
                                        @endphp
                                        {{ $hours }} Jam {{ $minutes }} Menit
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-center">Bukti Foto</h6>
                                @if ($item->bukti)
                                    <div id="carouselBukti{{ $item->id }}" class="carousel slide"
                                        data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            @foreach (explode(',', $item->bukti) as $index => $buktiPath)
                                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                    <a href="{{ Storage::url($buktiPath) }}" target="_blank">
                                                        <img src="{{ Storage::url($buktiPath) }}"
                                                            class="d-block w-100 rounded" alt="Bukti Foto"
                                                            style="max-height: 400px; object-fit: cover;"
                                                            id="carouselImage{{ $item->id }}_{{ $index }}">
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button class="carousel-control-prev" type="button"
                                            data-bs-target="#carouselBukti{{ $item->id }}" data-bs-slide="prev"
                                            id="carouselPrev{{ $item->id }}">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next" type="button"
                                            data-bs-target="#carouselBukti{{ $item->id }}" data-bs-slide="next"
                                            id="carouselNext{{ $item->id }}">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    </div>
                                @else
                                    <p class="text-center text-muted">Bukti belum diunggah</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach


    <!-- Tombol Pemicu Modal -->
    <td class="align-middle text-center">
        <i class="fa-solid fa-circle-info" data-bs-toggle="modal" data-bs-target="#detailModal"></i>
    </td>


    <!-- Modal Tambah Laporan Teknisi -->
    <div class="modal fade" id="tambahLaporanTeknisi" tabindex="-1" aria-labelledby="tambahLaporanTeknisiLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahLaporanTeknisiLabel">Selesaikan Aktivitas Terlebih Dahulu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="tambahLaporanTeknisiForm" method="POST" action="#" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <!-- Input untuk laporan -->
                        <div class="mb-3">
                            <label for="report" class="form-label">Catatan Siswa</label>
                            <textarea id="report" name="report" class="form-control" rows="3" required></textarea>
                        </div>
                        <!-- Input untuk bukti -->
                        <div class="mb-3">
                            <label for="bukti" class="form-label">Unggah Bukti</label>
                            <input type="file" id="bukti" name="bukti[]" class="form-control" multiple>
                        </div>
                        <!-- Tombol Submit -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-info">Simpan</button>
                        </div>
                    </form>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Menjalankan timer untuk setiap siswa yang sedang berlangsung
        @foreach ($siswa as $item)
            @if ($item->status === 'Sedang Berlangsung' && $item->waktu_mulai)
                startTimer('{{ $item->waktu_mulai }}', 'total-waktu-{{ $item->id }}');
            @endif
        @endforeach
    
        // Event listener untuk tombol dengan class .btn-danger
        document.querySelectorAll('.btn-danger').forEach(button => {
            button.addEventListener('click', function(event) {
                const id = this.getAttribute('data-id');
                const kategori = this.getAttribute('data-kategori');
                const report = this.getAttribute('data-report');
                const waktu_selesai = this.getAttribute('data-waktu_selesai');
    
                if (kategori === 'Keluar Dengan Teknisi') {
                    const modal = new bootstrap.Modal(document.getElementById('EditLaporanModal' + id));
                    modal.show();
    
                    const modalElement = document.getElementById('EditLaporanModal' + id);
                    if (modalElement) {
                        modalElement.querySelector('textarea[name="report"]').value = report;
                        modalElement.querySelector('input[name="waktu_selesai"]').value = waktu_selesai;
                    }
                } else {
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Aktivitas ini akan diselesaikan.",
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
                            if (modalElement) {
                                modalElement.querySelector('textarea[name="report"]').value = report;
                                modalElement.querySelector('input[name="waktu_selesai"]').value = waktu_selesai;
                            }
    
                            // **Tambahkan alert setelah tombol ditekan**
                            alert('Aktivitas sedang berlangsung!');
    
                        } else if (result.isDenied) {
                            const teknisiModal = new bootstrap.Modal(document.getElementById('tambahLaporanTeknisi'));
                            teknisiModal.show();
    
                            const teknisiForm = document.getElementById('tambahLaporanTeknisiForm');
                            if (teknisiForm) {
                                teknisiForm.setAttribute('action', `/siswa/updateAndCreate/${id}`);
                            }
                        }
                    });
                }
            });
        });
    });
    
    // Fungsi untuk memulai timer
    function startTimer(waktuMulai, elementId) {
        const startTime = new Date(waktuMulai).getTime();
    
        function updateTime() {
            const now = new Date().getTime();
            const elapsed = now - startTime;
    
            const hours = Math.floor((elapsed % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((elapsed % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((elapsed % (1000 * 60)) / 1000);
    
            // **Pastikan elemen ditemukan sebelum mengubah teks**
            const timerElement = document.getElementById(elementId);
            if (timerElement) {
                timerElement.textContent = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            }
        }
    
        updateTime();
        setInterval(updateTime, 1000);
    }
    
    </script>
    
</x-app-layout>