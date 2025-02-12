<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />


    <style>
        .pagination .page-item .page-link {
            background-color: skyblue !important;
            color: white !important;
            border: 1px solid skyblue;
        }
    
        .pagination .page-item.active .page-link {
            background-color: skyblue !important;
            border-color: skyblue;
        }
    
        .pagination .page-item .page-link:hover {
            background-color: royalblue !important;
            color: white !important;
        }
    </style>
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
                                    <h6 class="font-weight-semibold text-lg mb-0">Daftar Materi</h6>
                                    <p class="text-sm">Materi-Materi yang harus dipelajari</p>
                                </div>
                                <div class="ms-auto d-flex">
                                    <button type="button"
                                        class="btn btn-sm btn-dark btn-icon d-flex align-items-center me-2"
                                        data-bs-toggle="modal" data-bs-target="#tambahMateriModal">
                                        <span class="btn-inner--icon">
                                            <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 24 24" fill="currentColor" class="d-block me-2">
                                                <path
                                                    d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z" />
                                            </svg>
                                        </span>
                                        <span class="btn-inner--text">Tambah Materi</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 py-0">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th
                                                class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                                No</th>
                                            <th
                                                class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                                Materi</th>
                                            <th
                                                class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                                Detail</th>
                                            <th
                                                class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                                File Materi</th>
                                            <th
                                                class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                                Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($materitkj as $index => $item)
                                            <tr>
                                                <td class="align-middle text-center" rowspan="2">
                                                    <p class="text-sm text-dark font-weight-semibold mb-0">
                                                        {{ $index + 1 }}</p>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <p class="text-sm text-dark font-weight-semibold mb-0">
                                                        {{ $item->materi }}</p>
                                                </td>
                                                <td class="align-middle text-center text-sm">
                                                    <p class="text-sm text-dark mb-0">{{ $item->detail }}</p>
                                                </td>
                                                <td class="align-middle text-center">
                                                    @if ($item->file_materi)
                                                        <a href="{{ asset('storage/' . $item->file_materi) }}"
                                                            target="_blank" title="Lihat File">
                                                            <i class="fas fa-eye text-info mx-1"></i>
                                                        </a>
                                                        <a href="{{ asset('storage/' . $item->file_materi) }}" download
                                                            title="Unduh File">
                                                            <i class="fas fa-download text-success mx-1"></i>
                                                        </a>
                                                    @else
                                                        <span class="text-muted">Tidak ada file</span>
                                                    @endif
                                                </td>
                                                <td class="align-middle text-center">
                                                    <a href="#" data-bs-toggle="modal"
                                                        data-bs-target="#editMateriModal{{ $item->id }}"
                                                        title="Edit">
                                                        <i class="fas fa-edit text-warning mx-1"></i>
                                                    </a>

                                                    <a href="#" class="text-danger"
                                                        onclick="confirmDelete({{ $item->id }})">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                    <form id="delete-form-{{ $item->id }}"
                                                        action="{{ route('materitkj.destroy', $item->id) }}"
                                                        method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>

                                                </td>
                                            </tr>

                                            <tr></tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-between m-3 align-items-center">
                                <span class="text-muted">
                                    Page {{ $materitkj->currentPage() }} of {{ $materitkj->lastPage() }}
                                </span>
                                <div>
                                    {{ $materitkj->links() }}
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
    <div class="modal fade" id="tambahMateriModal" tabindex="-1" aria-labelledby="tambahMateriModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahMateriModalLabel">Tambah Materi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('materitkj.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="materi" class="form-label">Judul Materi</label>
                            <input type="text" class="form-control" name="materi" id="materi"
                                placeholder="Input Judul Materi" required>
                        </div>
                        <div class="mb-3">
                            <label for="detail" class="form-label">Detail</label>
                            <input type="text" class="form-control" name="detail" id="detail"
                                placeholder="Input Detail Materi" required>
                        </div>
                        <div class="mb-3">
                            <label for="file_materi" class="form-label">File Materi (PDF, Image, Doc, XLS, PPT,
                                TXT)</label>
                            <input type="file" class="form-control" name="file_materi" id="file_materi"
                                accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="jurusan" value="TKJ">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-info">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($materitkj as $item)
        <!-- Modal Edit -->
        <div class="modal fade" id="editMateriModal{{ $item->id }}" tabindex="-1"
            aria-labelledby="editMateriModalLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editMateriModalLabel{{ $item->id }}">Edit Materi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form action="{{ route('materitkj.update', $item->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="materi" class="form-label">Judul Materi</label>
                                <input type="text" class="form-control" name="materi" id="materi"
                                    value="{{ $item->materi }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="detail" class="form-label">Detail</label>
                                <input type="text" class="form-control" name="detail" id="detail"
                                    value="{{ $item->detail }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="file_materi" class="form-label">File Materi (PDF, Image, Doc,
                                    etc.)</label>
                                <input type="file" class="form-control" name="file_materi" id="file_materi"
                                    accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt">
                                @if ($item->file_materi)
                                    <p class="mt-2">File saat ini: <a
                                            href="{{ asset('storage/' . $item->file_materi) }}"
                                            target="_blank">{{ basename($item->file_materi) }}</a></p>
                                @endif
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
