<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <x-app.navbar />

    <div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
        <div class="pt-7 pb-6 bg-cover"
            style="background-image: url('../assets/img/header-orange-purple.jpg'); background-position: bottom;">
        </div>
        <div class="container">
            <div class="card card-body py-2 bg-transparent shadow-none position-relative">
                <div class="row">
                    <div class="col-auto">
                        <div class="avatar avatar-2xl rounded-circle position-relative mt-n7 border border-gray-100 border-4" style="cursor: pointer;">
                        @if (Auth::user()->pfp_path)
                        <img src="{{ Storage::url(Auth::user()->pfp_path) }}" alt="profile_image" class="w-100 h-100" id="profileImage" data-bs-toggle="modal" data-bs-target="#uploadImageModal" style="object-fit: cover">
                        @else
                        <img src="{{ asset('assets/img/img-8.jpg') }}" alt="profile_image" class="w-100 h-100" id="profileImage" data-bs-toggle="modal" data-bs-target="#uploadImageModal" style="object-fit: cover">
                        @endif
                        
                        <!-- Overlay edit icon pada gambar -->
                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center rounded-circle" 
                            style="background-color: rgba(0,0,0,0.3); opacity: 0; transition: opacity 0.3s;" 
                            onmouseover="this.style.opacity='1'" 
                            onmouseout="this.style.opacity='0'"
                            data-bs-toggle="modal" data-bs-target="#uploadImageModal">
                            <i class="fas fa-camera text-white fs-4"></i>
                        </div>
                    </div>

                    <!-- Modal untuk upload gambar -->
                    <div class="modal fade" id="uploadImageModal" tabindex="-1" aria-labelledby="uploadImageModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="uploadImageModalLabel">Ubah Foto Profil</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('profile.uploadPfp') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="text-center mb-3">
                                            @if (Auth::user()->pfp_path)
                                            <img src="{{ Storage::url(Auth::user()->pfp_path) }}" alt="Current profile" id="currentProfileImage" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                                            @else
                                            <img src="{{ asset('assets/img/img-8.jpg') }}" alt="Default profile" id="currentProfileImage" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                                            @endif
                                        </div>
                                        
                                        <div class="text-center mb-3">
                                            <img id="imagePreview" src="#" alt="Preview" class="rounded-circle d-none" style="width: 150px; height: 150px; object-fit: cover;">
                                        </div>
                                        <div class="mb-3">
                                            <label for="profileImageInput" class="form-label">Pilih gambar baru</label>
                                            <input class="form-control" type="file" id="profileImageInput" name="profile_image" accept="image/*">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Upload</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="col-auto my-auto">
                        <div class="h-100">
                            <h3 class="mb-0 font-weight-bold">
                                {{ Auth::user()->nama_lengkap }}
                            </h3>
                            <p class="mb-0">
                                {{ Auth::user()->username }}
                            </p>
                        </div>
                    </div>
                    <!-- Tombol Edit Lingkaran yang Memotong Background -->
                    <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3 text-sm-end position-relative">
                        <div class="position-absolute" style="top: -50px; right: 0;">
                            <button class="btn btn-primary rounded-circle p-0 d-flex align-items-center justify-content-center" 
                                    style="width: 50px; height: 50px; border: 4px solid #f8f9fa; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editProfileModal">
                                <i class="fas fa-pen fs-6"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Bagian baru untuk menampilkan data pengguna dalam bentuk tabel -->
            <h5 class="card-title mt-5 fs-3">Informasi Profil</h5>
            <div class="card mt-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <tbody>
                                <tr>
                                    <td class="w-25 fw-bold border-end p-3">Nama Lengkap</td>
                                    <td class="ps-3 p-3">{{ Auth::user()->nama_lengkap }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold border-end p-3">Nomor HP</td>
                                    <td class="ps-3 p-3">{{ Auth::user()->no_hp ? '+' : '' }}{{ Auth::user()->no_hp ?? 'Belum diatur' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold border-end p-3">Role</td>
                                    <td class="ps-3 p-3">
                                        <span class="badge text-bg-primary">{{ ucfirst(Auth::user()->role) }}</span>
                                    </td>
                                </tr>
                                @if(Auth::user()->role == 'siswa')
                                <tr>
                                    <td class="fw-bold border-end p-3">Jurusan</td>
                                    <td class="ps-3 p-3">{{ Auth::user()->jurusan ?? 'Belum diatur' }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="fw-bold border-end p-3">Username</td>
                                    <td class="ps-3 p-3">{{ Auth::user()->username }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold border-end p-3">Password</td>
                                    <td class="ps-3 p-3">
                                        <span class="text-muted">
                                            {{ str_repeat('•', strlen(Auth::user()->password)) }}
                                        </span>
                                        <button type="button" class="btn btn-sm btn-outline-primary ms-2" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                            Ganti Password
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- End bagian baru -->
            
        </div>
        
        <!-- Modal untuk menampilkan password -->
        <div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="passwordModalLabel">Password Anda</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            Silakan gunakan fitur "Ganti Password" jika Anda ingin mengubah password.
                        </div>
                        <span class="text-muted">Password: <strong>{{ Auth::user()->password }}</strong></span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modal Ganti Password -->
        <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('profile.changePassword') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title" id="changePasswordModalLabel">Ganti Password</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="currentPassword" class="form-label">Password Lama</label>
                                <input type="password" class="form-control" id="currentPassword" name="current_password" required>
                            </div>
                            <div class="mb-3">
                                <label for="newPassword" class="form-label">Password Baru</label>
                                <input type="password" class="form-control" id="newPassword" name="new_password" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" id="confirmPassword" name="new_password_confirmation" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- End modal -->

        <div class="container my-3 py-3"></div>
        <!-- Modal Edit Profil -->
        <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProfileModalLabel">Edit Profil</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="namaLengkap" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="namaLengkap" name="nama_lengkap" 
                                    value="{{ Auth::user()->nama_lengkap }}">
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                    value="{{ Auth::user()->username }}">
                            </div>
                            <div class="mb-3">
                                <label for="noHp" class="form-label">Nomor HP (62XXXXXXXXXXX)</label>
                                <input type="text" class="form-control" id="noHp" name="no_hp" 
                                    value="{{ Auth::user()->no_hp }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </main>
    <script>
    // Script untuk menampilkan preview gambar
    document.getElementById('profileImageInput').addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
        const currentImage = document.getElementById('currentProfileImage');
        const file = e.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                currentImage.classList.add('d-none');
            }
            
            reader.readAsDataURL(file);
        } else {
            preview.classList.add('d-none');
            currentImage.classList.remove('d-none');
        }
    });
    </script>
</x-app-layout>