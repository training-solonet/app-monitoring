<x-app-layout>
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
                                    <h6 class="font-weight-semibold text-lg mb-0">Daftar Pekerjaan</h6>
                                </div>
                                <div class="ms-auto d-flex">
                                    <button type="button" class="btn btn-sm btn-dark btn-icon d-flex align-items-center me-2"
                                        data-bs-toggle="modal" data-bs-target="#tambahAktivitasModal">
                                        <span class="btn-inner--icon">
                                            <i class="fas fa-plus"></i>
                                        </span>
                                        <span class="btn-inner--text">Tambah Pekerjaan</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="card-body px-0 py-0">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                                No</th>
                                            <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                                Pekerjaan</th>
                                            <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($aktivitas as $index => $item)
                                            <tr>
                                                <td class="align-middle text-center">{{ $index + 1 }}</td>
                                                <td class="align-middle text-center">{{ $item->nama_aktivitas }}</td>
                                                <td class="align-middle text-center">
                                                    <!-- Edit and Delete actions -->
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#editAktivitasModal{{ $item->id }}" class="text-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="#" class="text-danger" onclick="confirmDelete({{ $item->id }})">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>

                                                    <!-- Delete form -->
                                                    <form id="delete-form-{{ $item->id }}" action="{{ route('aktivitas.destroy', $item->id) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
            </div>
            <x-app.footer />
        </div>
    </main>

    <!-- Modal Tambah Pekerjaan -->
    <div class="modal fade" id="tambahAktivitasModal" tabindex="-1" aria-labelledby="tambahAktivitasModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahAktivitasModalLabel">Tambah Pekerjaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('aktivitas.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_aktivitas" class="form-label">Nama Pekerjaan</label>
                            <input type="text" class="form-control" name="nama_aktivitas" id="nama_aktivitas" placeholder="Masukkan nama Pekerjaan" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-info">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Aktivitas -->
    @foreach ($aktivitas as $item)
        <div class="modal fade" id="editAktivitasModal{{ $item->id }}" tabindex="-1" aria-labelledby="editAktivitasModalLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editAktivitasModalLabel{{ $item->id }}">Edit Aktivitas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('aktivitas.update', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nama_aktivitas" class="form-label">Nama Aktivitas</label>
                                <input type="text" class="form-control" name="nama_aktivitas" id="nama_aktivitas" value="{{ $item->nama_aktivitas }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-info">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</x-app-layout>

<script>
    // Confirm delete function
    function confirmDelete(id) {
        if (confirm('Apakah Anda yakin ingin menghapus aktivitas ini?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
