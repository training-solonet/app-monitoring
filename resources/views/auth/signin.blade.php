<x-guest-layout>
    <main class="main-content mt-0" style="overflow: hidden; min-height: 100vh; background: linear-gradient(135deg, #1f1c2c, #928dab);">
        <section>
            <div class="page-header min-vh-100 d-flex align-items-center overflow-hidden">
                <div class="container">
                    <div class="d-flex justify-content-center mb-4">
                        <img src="http://absensi.connectis.my.id/logo.png" style="height:8rem; filter: drop-shadow(0px 4px 8px rgba(0,0,0,0.5));" alt="Logo">
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-xl-4 col-md-6">
                            <div class="card p-4 rounded-3 shadow-lg border-0" style="background: linear-gradient(135deg, #ffffff, #f8f9fa); transition: transform 0.3s; animation: slideIn 0.5s ease;">
                                <div class="card-header pb-0 text-center bg-transparent">
                                    <h3 class="font-weight-black text-dark display-6 mb-0">Monitoring App</h3>
                                    <p class="text-muted mt-2">Silahkan Masukkan Data Login</p>
                                </div>
                                <div class="text-center mt-1">
                                    @if (session('status'))
                                        <div class="mb-4 font-medium text-sm text-success">
                                            {{ session('status') }}
                                        </div>
                                    @endif
                                    @error('message')
                                        <div class="alert alert-danger text-sm" role="alert">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="card-body">
                                    <form role="form" class="text-start" method="POST" action="sign-in">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="username" class="form-label text-dark">Username</label>
                                            <input type="text" id="username" name="username"
                                                class="form-control rounded-pill" placeholder="Masukan Username Anda"
                                                value="{{ old('username') }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="password" class="form-label text-dark">Kata sandi</label>
                                            <div class="form-group password-container">
                                                <input type="password" id="password" name="password" class="form-control rounded-pill" placeholder="Masukan Kata Sandi Anda" required>
                                                <i class="eye-icon bi bi-eye" id="togglePassword"></i>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-dark w-100 mt-4 mb-3 rounded-pill" 
                                                style="background: linear-gradient(135deg, #4b79a1, #283e51); border: none; transition: transform 0.2s; font-weight: bold;">
                                                Sign in
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-guest-layout>

<style>
    /* Smooth slide-in animation */
    @keyframes slideIn {
        from {
            transform: translateY(20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Button hover effect */
    .btn-dark:hover {
        transform: scale(1.05);
        background: linear-gradient(135deg, #3b5998, #192f4d);
    }

    /* Card shadow effect */
    .card:hover {
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
        transform: translateY(-4px);
    }
</style>
