<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
                            <hr>
                            <form id="filterForm" method="GET" action="{{ route('monitoring.index') }}">
                                <div class="row g-2 align-items-end">
                                    <div class="col-sm-3">
                                        <label for="nama_siswa" class="form-label">Nama Siswa</label>
                                        <select id="nama_siswa" name="nama_siswa" class="form-select" onchange="document.getElementById('filterForm').submit();">
                                            <option disabled selected>Pilih Siswa</option>
                                            @foreach ($siswa_monitoring as $siswa)
                                                <option value="{{ $siswa->username }}" {{ request('nama_siswa') == $siswa->username ? 'selected' : '' }}>
                                                    {{ $siswa->username }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="status" class="form-label">Status</label>
                                        <select id="status" name="status" class="form-select" onchange="document.getElementById('filterForm').submit();">
                                            <option disabled selected>Pilih Status</option>
                                            <option value="to do" {{ request('status') == 'to do' ? 'selected' : '' }}>To Do</option>
                                            <option value="doing" {{ request('status') == 'doing' ? 'selected' : '' }}>Doing</option>
                                            <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Done</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="jurusan" class="form-label">Jurusan</label>
                                        <select id="jurusan" name="jurusan" class="form-select" onchange="document.getElementById('filterForm').submit();">
                                            <option disabled selected>Pilih Jurusan</option>
                                            <option value="TKJ" {{ request('jurusan') == 'TKJ' ? 'selected' : '' }}>TKJ</option>
                                            <option value="RPL" {{ request('jurusan') == 'RPL' ? 'selected' : '' }}>RPL</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                                        <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}" onchange="document.getElementById('filterForm').submit();">
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                                        <input type="date" id="tanggal_selesai" name="tanggal_selesai" class="form-control" value="{{ request('tanggal_selesai') }}" onchange="document.getElementById('filterForm').submit();">
                                    </div>
                                    <div class="col-sm-1">
                                        <a href="{{ route('monitoring.index') }}" class="btn btn-outline-secondary btn-sm mb-1 w-100">Reset</a>
                                    </div>
                                </div>
                            </form>
                            
                        </div>
                        

                        <div class="card-body">
                            <div class="table-responsive p-0">
                                <table class="table table-striped">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="text-center text-xs font-weight-semibold opacity-7">No</th>
                                            <th class="text-center text-xs font-weight-semibold opacity-7">Nama Siswa</th>
                                            <th class="text-center text-xs font-weight-semibold opacity-7">Jurusan</th>
                                            <th class="text-center text-xs font-weight-semibold opacity-7">Kategori</th>
                                            <th class="text-center text-xs font-weight-semibold opacity-7">Materi</th>
                                            <th class="text-center text-xs font-weight-semibold opacity-7">Report</th>
                                            <th class="text-center text-xs font-weight-semibold opacity-7">Waktu Mulai</th>
                                            <th class="text-center text-xs font-weight-semibold opacity-7">Waktu Selesai</th>
                                            <th class="text-center text-xs font-weight-semibold opacity-7">Status</th>
                                            <th class="text-center text-xs font-weight-semibold opacity-7">Bukti</th>
                                            <th class="text-center text-xs font-weight-semibold opacity-7">Aksi</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody class="bg-white">
                                        @foreach ($monitoring as $index => $item)
                                            <tr>
                                                <td class="align-middle text-center">{{ $index + 1 }}</td>
                                                <td class="align-middle text-center">{{ $item->siswa_monitoring?->username ?? '' }}</td>
                                                <td class="align-middle text-center">{{ $item->siswa_monitoring?->jurusan ?? '' }}</td>
                                                <td class="align-middle text-center">{{ $item->kategori }}</td>
                                                <td class="align-middle text-center">{{ $item->materitkj?->materi ?? 'Tidak ada materi' }}</td>
                                                <td class="align-middle text-center">{{ $item->report }}</td>
                                                <td class="align-middle text-center">{{ $item->waktu_mulai }}</td>
                                                <td class="align-middle text-center">{{ $item->waktu_selesai }}</td>
                                                <td class="align-middle text-center">{{ $item->status }}</td>
                                                <td></td>
                                                <td></td>
                                                <td class="align-middle text-center">
                                                    <a href="#" class="text-danger" onclick="confirmDelete({{ $item->id }})">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                    <form id="delete-form-{{ $item->id }}" action="{{ route('monitoring.destroy', $item->id) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
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
