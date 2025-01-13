<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        <x-app.navbar />
        <div class="container-fluid px-5 py-4">
            <div class="mt-4 row">
                <div class="col-12">
                    <div class="card shadow-lg border-radius-lg">
                        <div class="card-header border-bottom pb-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="font-weight-bold text-lg mb-0">Data Monitoring Siswa</h6>
                            </div>
                            <hr>
                            <form id="filterForm" method="GET" action="{{ route('monitoring.index') }}">
                                <div class="row g-2 align-items-end">
                                    <div class="col-sm-3">
                                        <label for="nama_siswa" class="form-label">Nama Siswa</label>
                                        <select id="nama_siswa" name="nama_siswa" class="form-select"
                                            onchange="document.getElementById('filterForm').submit();">
                                            <option disabled selected>Pilih Siswa</option>
                                            @foreach ($siswa_monitoring as $siswa)
                                                <option value="{{ $siswa->username }}"
                                                    {{ request('nama_siswa') == $siswa->username ? 'selected' : '' }}>
                                                    {{ $siswa->username }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="status" class="form-label">Status</label>
                                        <select id="status" name="status" class="form-select"
                                            onchange="document.getElementById('filterForm').submit();">
                                            <option disabled selected>Pilih Status</option>
                                            <option value="Mulai" {{ request('status') == 'Mulai' ? 'selected' : '' }}>
                                                Mulai</option>
                                            <option value="Sedang Berlangsung"
                                                {{ request('status') == 'Sedang Berlangsung' ? 'selected' : '' }}>Sedang
                                                Berlangsung</option>
                                            <option value="Selesai"
                                                {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="jurusan" class="form-label">Jurusan</label>
                                        <select id="jurusan" name="jurusan" class="form-select"
                                            onchange="document.getElementById('filterForm').submit();">
                                            <option disabled selected>Pilih Jurusan</option>
                                            <option value="TKJ" {{ request('jurusan') == 'TKJ' ? 'selected' : '' }}>
                                                TKJ</option>
                                            <option value="RPL" {{ request('jurusan') == 'RPL' ? 'selected' : '' }}>
                                                RPL</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                                        <input type="date" id="tanggal_mulai" name="tanggal_mulai"
                                            class="form-control" value="{{ request('tanggal_mulai') }}"
                                            onchange="document.getElementById('filterForm').submit();">
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                                        <input type="date" id="tanggal_selesai" name="tanggal_selesai"
                                            class="form-control" value="{{ request('tanggal_selesai') }}"
                                            onchange="document.getElementById('filterForm').submit();">
                                    </div>
                                    <div class="col-sm-1">
                                        <a href="{{ route('monitoring.index') }}"
                                            class="btn btn-outline-secondary btn-sm mb-1 w-100">Reset</a>
                                    </div>
                                </div>
                            </form>

                        </div>


                        <div class="card-body">
                            <div class="table-responsive p-0">
                                <table class="table table-custom">
                                    <thead class="">
                                        <tr>
                                            <th class="text-center text-xs font-weight-semibold opacity-7">No</th>
                                            <th class="text-center text-xs font-weight-semibold opacity-7">Nama Siswa
                                            </th>
                                            <th class="text-center text-xs font-weight-semibold opacity-7">Jurusan</th>
                                            <th class="text-center text-xs font-weight-semibold opacity-7">Kategori</th>
                                            <th class="text-center text-xs font-weight-semibold opacity-7">Materi</th>
                                            <th class="text-center text-xs font-weight-semibold opacity-7">Report</th>
                                            <th class="text-center text-xs font-weight-semibold opacity-7">Waktu Mulai
                                            </th>
                                            <th class="text-center text-xs font-weight-semibold opacity-7">Waktu Selesai
                                            </th>
                                            <th class="text-center text-xs font-weight-semibold opacity-7">Status</th>
                                            <th class="text-center text-xs font-weight-semibold opacity-7">Bukti</th>
                                            <th class="text-center text-xs font-weight-semibold opacity-7">Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody class="">
                                        @foreach ($monitoring as $index => $item)
                                            <tr>
                                                <td class="align-middle text-center">{{ $index + 1 }}</td>
                                                <td class="align-middle text-center">
                                                    {{ $item->siswa_monitoring?->username ?? '' }}</td>
                                                <td class="align-middle text-center">
                                                    {{ $item->siswa_monitoring?->jurusan ?? '' }}</td>
                                                <td class="align-middle text-center">{{ $item->kategori }}</td>
                                                <td class="align-middle text-center">
                                                    {{ $item->materitkj?->materi ?? 'Tidak ada materi' }}</td>
                                                <td class="align-middle text-center">{{ $item->report }}</td>
                                                <td class="align-middle text-center">
                                                    {{ \Carbon\Carbon::parse($item->waktu_mulai)->locale('id')->translatedFormat('l, d M Y H:i') }}
                                                </td>
                                                <td class="align-middle text-center">
                                                    {{ \Carbon\Carbon::parse($item->waktu_selesai)->locale('id')->translatedFormat('l, d M Y H:i') }}
                                                </td>
                                                <td class="align-middle text-center">{{ $item->status }}</td>
                                                <td class="align-middle text-center">
                                                    <a class="mb-0" data-bs-toggle="modal"
                                                        data-bs-target="#ViewBuktiModal{{ $item->id }}">
                                                        <i class="fa-regular fa-image text-info"></i>
                                                    </a>
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
                                                                    data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
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
                                                                    <p class="text-center">Bukti belum diunggah</p>
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
                                                    <a href="#" class="text-danger"
                                                        onclick="confirmDelete({{ $item->id }})">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                    <form id="delete-form-{{ $item->id }}"
                                                        action="{{ route('monitoring.destroy', $item->id) }}"
                                                        method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </td>
                                    </tbody>

                                </table>
                            </div>
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
    </main>
</x-app-layout>
