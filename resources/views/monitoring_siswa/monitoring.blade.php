<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid px-5 py-4">
            <div class="mt-4 row">
                <div class="col-12">
                    <div class="card shadow-lg border-radius-lg">
                        <div class="card-header border-bottom pb-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="font-weight-bold text-lg mb-0">Data Monitoring Siswa</h6>
                                <form method="GET" action="{{ route('monitoring.index') }}" class="d-flex">
                                <input type="text" name="search" class="form-control" placeholder="Cari" value="{{ request('search') }}">
                                </form>
                            </div>
                            <form id="filterForm" method="GET" action="{{ route('monitoring.index') }}">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-3">
                                        <label for="nama_siswa" class="form-label">Nama Siswa</label>
                                        <select id="nama_siswa" name="nama_siswa" class="form-select" onchange="document.getElementById('filterForm').submit();">
                                            <option value="">Pilih Siswa</option>
                                            @foreach ($siswa_monitoring as $siswa)
                                                <option value="{{ $siswa->username }}" {{ request('nama_siswa') == $siswa->username ? 'selected' : '' }}>
                                                    {{ $siswa->username }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select id="status" name="status" class="form-select" onchange="document.getElementById('filterForm').submit();">
                                            <option value="">Pilih Status</option>
                                            <option value="to do" {{ request('status') == 'to do' ? 'selected' : '' }}>To Do</option>
                                            <option value="doing" {{ request('status') == 'doing' ? 'selected' : '' }}>Doing</option>
                                            <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Done</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="jurusan" class="form-label">Jurusan</label>
                                        <select id="jurusan" name="jurusan" class="form-select" onchange="document.getElementById('filterForm').submit();">
                                            <option value="">Pilih Jurusan</option>
                                            <option value="TKJ" {{ request('jurusan') == 'TKJ' ? 'selected' : '' }}>TKJ</option>
                                            <option value="RPL" {{ request('jurusan') == 'RPL' ? 'selected' : '' }}>RPL</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                                        <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}" onchange="document.getElementById('filterForm').submit();">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                                        <input type="date" id="tanggal_selesai" name="tanggal_selesai" class="form-control" value="{{ request('tanggal_selesai') }}" onchange="document.getElementById('filterForm').submit();">
                                    </div>
                                    <a href="{{ route('monitoring.index') }}" class="btn btn-secondary">Reset Filter</a>
                                </div>
                            </form>
                        </div>

                        <div class="card-body px-4 py-3">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered">
                                    <thead class="bg-white text-dark">
                                        <tr>
                                            <th class="text-center text-xs font-weight-semibold">No</th>
                                            <th class="text-center text-xs font-weight-semibold">Nama Siswa</th>
                                            <th class="text-center text-xs font-weight-semibold">Jurusan</th>
                                            <th class="text-center text-xs font-weight-semibold">Kategori</th>
                                            <th class="text-center text-xs font-weight-semibold">Materi</th>
                                            <th class="text-center text-xs font-weight-semibold">Report</th>
                                            <th class="text-center text-xs font-weight-semibold">Waktu Mulai</th>
                                            <th class="text-center text-xs font-weight-semibold">Waktu Selesai</th>
                                            <th class="text-center text-xs font-weight-semibold">Status</th>
                                            <th class="text-center text-xs font-weight-semibold">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($monitoring as $index => $item)
                                            <tr>
                                                <td class="align-middle text-center">{{ $index + 1 }}</td>
                                                <td class="align-middle text-center">{{ $item->siswa_monitoring?->username ?? ''  }}</td>
                                                <td class="align-middle text-center">{{$item->siswa_monitoring?->jurusan ?? ''  }}</td>
                                                <td class="align-middle text-center">{{ $item->kategori }}</td>
                                                <td class="align-middle text-center">{{$item->materitkj?->materi ?? 'Tidak ada materi' }}</td>
                                                <td class="align-middle text-center">{{ $item->report }}</td>
                                                <td class="align-middle text-center">{{ $item->waktu_mulai }}</td>
                                                <td class="align-middle text-center">{{ $item->waktu_selesai }}</td>
                                                <!-- <td class="align-middle text-center">{{ $item->total_waktu }}</td> -->
                                                <td class="align-middle text-center">{{ $item->status }}</td>
                                                <td class="align-middle text-center">
                                                    <a href="{{ route('monitoring.edit', $item->id) }}"
                                                        class="btn btn-warning btn-sm">Edit</a>
                                                    <form action="{{ route('monitoring.destroy', $item->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>
