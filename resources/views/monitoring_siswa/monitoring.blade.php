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
                                                    <h6 class="font-weight-semibold text-lg mb-3">Data Monitoring Siswa</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body px-0 py-0">
                                            <div class="table-responsive p-0">
                                                <div class="table-responsive p-0">
                                                    <table class="table align-items-center mb-0">
                                                        <thead class="bg-gray-100">
                                                            <tr>
                                                                <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">No
                                                                </th>
                                                                <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">
                                                                    Nama Siswa</th>
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
                                                                            {{ $item->siswa }}</p>
                                                                    </td>
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

{{-- <!-- Modal -->
<div class="modal fade" id="tambahAnggotaModal" tabindex="-1" aria-labelledby="tambahAnggotaModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahAnggotaModalLabel">Tambah Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('materi.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="materi" class="form-label">Nama materi</label>
                        <input type="text" class="form-control" id="materi" name="materi" placeholder="materi" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-info">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> --}}
