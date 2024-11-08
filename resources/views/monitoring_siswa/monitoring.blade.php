<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid px-5 py-4">
            <div class="mt-4 row">
                <div class="col-12">
                    <div class="card shadow-lg border-radius-lg">
                        <div class="card-header border-bottom pb-0">
                            <h6 class="font-weight-bold text-lg mb-1">Data Monitoring Siswa</h6>
                            <form method="GET" action="{{ route('monitoring.index') }}" class="m-0 mt-3">
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        <label for="status" class="form-label">Nama Siswa</label>
                                        <select id="status" name="status" class="form-select">
                                            <option value="">-- Pilih Nama Siswa --</option>
                                            <option value="to do" {{ request('status') == 'to do' ? 'selected' : '' }}>To Do</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="jurusan" class="form-label">Jurusan</label>
                                        <select id="jurusan" name="jurusan" class="form-select">
                                            <option value="">-- Pilih Jurusan --</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                                        <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                                        <input type="date" id="tanggal_selesai" name="tanggal_selesai" class="form-control" value="{{ request('tanggal_selesai') }}">
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="card-body px-0 py-2">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered">
                                    <thead class="bg-white text-dark">
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
                                            <th class="text-center text-xs font-weight-semibold opacity-7">Total Waktu</th>
                                            <th class="text-center text-xs font-weight-semibold opacity-7">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($monitoring as $index => $item)
                                            <tr>
                                                <td class="align-middle text-center">{{ $index + 1 }}</td>
                                                <td class="align-middle text-center">{{ $item->nama_siswa }}</td>
                                                <td class="align-middle text-center">{{ $item->jurusan }}</td>
                                                <td class="align-middle text-center">{{ $item->kategori }}</td>
                                                <td class="align-middle text-center">{{ $item->materi }}</td>
                                                <td class="align-middle text-center">{{ $item->report }}</td>
                                                <td class="align-middle text-center">
                                                    {!! $item->waktu_mulai ? \Carbon\Carbon::parse($item->waktu_mulai)->translatedFormat('d F Y, H:i') : '<em>Belum Dimulai</em>' !!}
                                                </td>
                                                <td class="align-middle text-center">
                                                    {!! $item->waktu_selesai ? \Carbon\Carbon::parse($item->waktu_selesai)->translatedFormat('d F Y, H:i') : '<em>Belum Berakhir</em>' !!}
                                                </td>
                                                <td class="align-middle text-center">
                                                    <span class="badge badge-sm border-0 text-uppercase {{ $item->status == 'to do' ? 'bg-warning text-dark' : 'bg-success text-white' }}">
                                                        {{ $item->status }}
                                                    </span>
                                                </td>
                                                <td class="align-middle text-center">{{ $item->total_waktu }}</td>
                                                <td class="align-middle text-center">
                                                    <!-- Aksi buttons here -->
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Pagination Footer -->
                        <div class="card-footer d-flex justify-content-between align-items-center bg-white">
                            <p class="font-weight-semibold mb-0 text-dark text-sm">Page 1 of 10</p>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-info">Previous</button>
                                <button class="btn btn-sm btn-outline-info">Next</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>
