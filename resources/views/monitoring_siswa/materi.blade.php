<x-app-layout>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
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
                                                    <h6 class="font-weight-semibold text-lg mb-3">Daftar materi</h6>
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
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @foreach ($materi as $index => $item)
                                                                <tr>
                                                                    <td class="align-middle text-center" rowspan="2">
                                                                        <p
                                                                            class="text-sm text-dark font-weight-semibold mb-0">
                                                                            {{ $index + 1 }}</p>
                                                                    </td>
                                                                    <td class="align-middle text-center">
                                                                        <p
                                                                            class="text-sm text-dark font-weight-semibold mb-0">
                                                                            {{ $item->materi }}</p>
                                                                    </td>
                                                                    <td class="align-middle text-center text-sm">
                                                                        <p class="text-sm text-dark mb-0">
                                                                            {{ $item->detail }}</p>
                                                                    </td>
                                                                    <td class="align-middle text-center">
                                                                        @if ($item->file_materi)
                                                                            <a href="{{ asset('storage/' . $item->file_materi) }}"
                                                                                target="_blank" title="Lihat File">
                                                                                <i
                                                                                    class="fas fa-eye text-info mx-1"></i>
                                                                            </a>
                                                                            <a href="{{ asset('storage/' . $item->file_materi) }}"
                                                                                download title="Unduh File">
                                                                                <i
                                                                                    class="fas fa-download text-success mx-1"></i>
                                                                            </a>
                                                                        @else
                                                                            <span class="text-muted">Tidak ada
                                                                                file</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>

                                                                <tr></tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between m-3 align-items-center">
                                                <span class="text-muted">
                                                    Page {{ $materi->currentPage() }} of {{ $materi->lastPage() }}
                                                </span>
                                                <div>
                                                    {{ $materi->links() }}
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
