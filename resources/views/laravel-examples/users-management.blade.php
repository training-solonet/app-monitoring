<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="px-5 py-4 container-fluid">
            <div class="mt-4 row">
                <div class="col-12">
                    <div class="alert alert-dark text-sm" role="alert">
                        <strong>Add, Edit, Delete features are not functional!</strong> This is a
                        <strong>PRO</strong> feature ! Click <a href="#" target="_blank" class="text-bold">here</a>
                        to see the <strong>PRO</strong> product!
                    </div>
                    <div class="card">
                        <div class="pb-0 card-header">
                            <div class="row">
                                <div class="col-6">
                                    <h5 class="">Pengelolaan Pengguna</h5>
                                    <p class="mb-0 text-sm">
                                        Di sini Anda dapat mengelola pengguna.
                                    </p>
                                </div>
                                <div class="col-6 text-end">
                                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#tambahAnggotaModal">
                                        Tambah Anggota
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="">
                                @if (session('success'))
                                    <div class="alert alert-success" role="alert" id="alert">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                @if (session('error'))
                                    <div class="alert alert-danger" role="alert" id="alert">
                                        {{ session('error') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table text-secondary text-center">
                                <thead>
                                    <tr>
                                        <th
                                            class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            ID</th>
                                        <th
                                            class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Photo</th>
                                        <th
                                            class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Name</th>
                                        <th
                                            class="text-left text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Username</th>
                                        <th
                                            class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Role</th>
                                        <th
                                            class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Jurusan</th>
                                        <th
                                            class="text-center text-uppercase font-weight-bold bg-transparent border-bottom text-secondary">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="align-middle bg-transparent border-bottom">1</td>
                                        <td class="align-middle bg-transparent border-bottom">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <img src="../assets/img/team-1.jpg" class="rounded-circle mr-2"
                                                    alt="user1" style="height: 36px; width: 36px;">
                                            </div>
                                        </td>
                                        <td class="align-middle bg-transparent border-bottom">Fathoni</td>
                                        <td class="align-middle bg-transparent border-bottom">admin@softui.com</td>
                                        <td class="text-center align-middle bg-transparent border-bottom">Admin</td>
                                        <td class="text-center align-middle bg-transparent border-bottom">TKJ</td>
                                        <td class="text-center align-middle bg-transparent border-bottom">
                                            <a href="#"><i class="fas fa-user-edit" aria-hidden="true"></i></a>
                                            <a href="#"><i class="fas fa-trash" aria-hidden="true"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="align-middle bg-transparent border-bottom">2</td>
                                        <td class="align-middle bg-transparent border-bottom">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <img src="../assets/img/team-2.jpg" class="rounded-circle mr-2"
                                                    alt="user2" style="height: 36px; width: 36px;">
                                            </div>
                                        </td>
                                        <td class="align-middle bg-transparent border-bottom">Ifkar</td>
                                        <td class="align-middle bg-transparent border-bottom">creator@softui.com</td>
                                        <td class="text-center align-middle bg-transparent border-bottom">Creator</td>
                                        <td class="text-center align-middle bg-transparent border-bottom">RPL</td>
                                        <td class="text-center align-middle bg-transparent border-bottom">
                                            <a href="#"><i class="fas fa-user-edit" aria-hidden="true"></i></a>
                                            <a href="#"><i class="fas fa-trash" aria-hidden="true"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

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
 <div class="modal fade" id="tambahAnggotaModal" tabindex="-1" aria-labelledby="tambahAnggotaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahAnggotaModalLabel">Tambah Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" placeholder="Nama Lengkap">
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" placeholder="Username">
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role">
                            <option selected>Pilih Role</option>
                            <option value="pembimbing">Pembimbing</option>
                            <option value="siswa">Siswa</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jurusan" class="form-label">Jurusan</label>
                        <select class="form-select" id="role">
                            <option selected>Pilih Jurusan</option>
                            <option value="tkj">Teknik Komputer Jaringan</option>
                            <option value="rpl">Rekayasa Perangkat Lunak</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-info">Simpan</button>
            </div>
        </div>
    </div>
</div>
