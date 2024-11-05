<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="px-5 py-4 container-fluid">
            <div class="mt-4 row">
                <div class="col-12">
                    <div class="card">
                        <div class="pb-0 card-header">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card border shadow-xs mb-4">
                                        <div class="card-header border-bottom pb-0">
                                            <div class="d-sm-flex align-items-center">
                                                <div>
                                                    <h6 class="font-weight-semibold text-lg mb-0">Daftar User</h6>
                                                    <p class="text-sm">Tambahkan User</p>
                                                </div>
                                                <div class="ms-auto d-flex">
                                                    <button type="button"
                                                        class="btn btn-sm btn-dark btn-icon d-flex align-items-center me-2"
                                                        data-bs-toggle="modal" data-bs-target="#tambahAnggotaModal">
                                                        <span class="btn-inner--icon">
                                                            <svg width="16" height="16"
                                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                                fill="currentColor" class="d-block me-2">
                                                                <path
                                                                    d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z" />
                                                            </svg>
                                                        </span>
                                                        <span class="btn-inner--text">Tambah User</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body px-0 py-0">

                                            <div class="table-responsive p-0">
                                                <div class="table-responsive p-0">
                                                    <table class="table align-items-center mb-0">
                                                        <thead class="bg-gray-100">
                                                            <tr>
                                                                <th
                                                                    class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                                                    No
                                                                </th>
                                                                <th
                                                                    class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                                                    Username</th>
                                                                <th
                                                                    class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                                                    Password</th>
                                                                <th
                                                                    class="text-center text-secondary font-weight-semibold text-xs opacity-7">
                                                                    tanggal mulai</th>
                                                                    <th
                                                                    class="text-center text-secondary font-weight-semibold text-xs opacity-7">
                                                                    tanggal selesai</th>
                                                                    <th
                                                                        class="text-center text-secondary font-weight-semibold text-xs opacity-7">
                                                                        masa pkl</th>
                                                                <th
                                                                    class="text-center text-secondary font-weight-semibold text-xs opacity-7">
                                                                    Status</th>
                                                                <th
                                                                    class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                                                    Aksi</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @foreach ($user as $index => $item)
                                                                <tr>
                                                                    <td class="align-middle text-center" rowspan="2">
                                                                        <p
                                                                            class="text-sm text-dark font-weight-semibold mb-0">
                                                                            {{ $index + 1 }}</p>
                                                                    </td>
                                                                    <td class="align-middle text-center">
                                                                        <p
                                                                            class="text-sm text-dark font-weight-semibold mb-0">
                                                                            {{ $item->username }}</p>
                                                                    </td>
                                                                    <td class="align-middle text-center text-sm">
                                                                        <p class="text-sm text-dark mb-0">
                                                                            {{ $item->password }}</p>
                                                                    </td>

                                                                    <td class="align-middle text-center text-sm">
                                                                        <p class="text-sm text-dark mb-0">
                                                                            {{ $item->tanggal_mulai }}
                                                                        </p>
                                                                    </td>

                                                                    <td class="align-middle text-center text-sm">
                                                                        <p class="text-sm text-dark mb-0">
                                                                            {{ $item->tanggal_selesai }}
                                                                        </p>
                                                                    </td>

                                                                    <td class="align-middle text-center text-sm">
                                                                        <p class="text-sm text-dark mb-0">
                                                                            {{ $item->masa_pkl }}
                                                                        </p>
                                                                    </td>

                                                                    <td class="align-middle text-center">
                                                                        @if ($item->status == 'Aktif')
                                                                            <span
                                                                                class="badge badge-sm border border-success text-uppercase text-success bg-success">{{ $item->status }}</span>
                                                                        @elseif($item->status == 'Tidak Aktif')
                                                                            <span
                                                                                class="badge badge-sm border border-secondary text-uppercase text-secondary bg-secondary">{{ $item->status }}</span>
                                                                        @endif
                                                                    </td>



                                                                    <td class="align-middle text-center">
                                                                        <button type="button"
                                                                            class="btn btn-warning btn-sm mb-0"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#editModal{{ $item->id }}">
                                                                            Edit
                                                                        </button>
                                                                    </td>
                                                                    </form>
                                                                    </td>
                                                                </tr>

                                                                <tr>

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
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <x-app.footer />
    </main>

</x-app-layout>

<script src="/assets/js/plugins/datatables.js"></script>
<script>
    const dataTableBasic = new simpleDatatables.DataTable("#datatable-search", {
        searchable: true,
        fixedHeight: true,
        columns: [{
            select: [2, 6],
            sortable: false
        }]
    });
</script>

<!-- Modal Tambah User -->
<div class="modal fade" id="tambahAnggotaModal" tabindex="-1" aria-labelledby="tambahAnggotaModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahAnggotaModalLabel">Tambah Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('useradmin.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="username" class="form-control" id="username" name="username"
                            placeholder="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Password" required>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_mulai" class="form-label">tanggal mulai</label>
                        <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai"
                            placeholder="tanggal_mulai" required>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_selesai" class="form-label">tanggal_</label>
                        <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai"
                            placeholder="tanggal_selesai" required>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="status" value="Aktif">
                        <input type="hidden" name="role" value="admin">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-info">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- <!--Modal Edit--> --}}
@foreach ($user as $item)
    <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1"
        aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel{{ $item->id }}">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('useradmin.update', $item->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username"
                                value="{{ $item->username }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="text" class="form-control" id="password" name="password"
                                value="{{ $item->password }}" required>
                        </div>
                        <div class="mb-3">
                            <select name="status" id="status" class="form-select">
                                <option value="Aktif">Aktif</option>
                                <option value="Tidak Aktif">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
