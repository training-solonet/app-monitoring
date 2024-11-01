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
                                                    <h6 class="font-weight-semibold text-lg mb-0">Daftar Jurusan</h6>
                                                    <p class="text-sm">Tambahkan Jurusan</p>
                                                </div>
                                                <div class="ms-auto d-flex">
                                                    <button type="button"
                                                        class="btn btn-sm btn-dark btn-icon d-flex align-items-center me-2" data-bs-toggle="modal" data-bs-target="#tambahAnggotaModal">
                                                        <span class="btn-inner--icon">
                                                            <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg"
                                                                viewBox="0 0 24 24" fill="currentColor" class="d-block me-2">
                                                                <path
                                                                    d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z" />
                                                            </svg>
                                                        </span>
                                                        <span class="btn-inner--text">Tambah Jurusan</span>
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
                                                <div class="table-responsive p-0">
                                                    <table class="table align-items-center mb-0">
                                                        <thead class="bg-gray-100">
                                                            <tr>
                                                                <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">No
                                                                </th>
                                                                <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                                                    Nama Jurusan</th>
                                                                <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                                                    Aksi</th>
                                                            </tr>
                                                        </thead>
                        
                                                        <tbody>
                                                            @foreach ($jurusan as $index => $item)
                                                                <tr>
                                                                    <td class="align-middle text-center" rowspan="2">
                                                                        <p class="text-sm text-dark font-weight-semibold mb-0">
                                                                            {{ $index + 1 }}</p>
                                                                    </td>
                                                                    <td class="align-middle text-center">
                                                                        <p class="text-sm text-dark font-weight-semibold mb-0">
                                                                            {{ $item->jurusan }}</p>
                                                                    </td>
                                                                    <td class="align-middle text-center">
                        
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

<!-- Modal -->
<div class="modal fade" id="tambahAnggotaModal" tabindex="-1" aria-labelledby="tambahAnggotaModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahAnggotaModalLabel">Tambah Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('jurusan.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="Jurusan" class="form-label">Nama Jurusan</label>
                        <input type="text" class="form-control" id="Jurusan" name="jurusan" placeholder="Jurusan" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-info">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
