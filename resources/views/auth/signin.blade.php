<x-guest-layout>
    <style>
        
        .custom-checkbox {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        color: white;
        font-size: 0.9rem;
        }

        .custom-checkbox input {
        appearance: none;
        -webkit-appearance: none;
        width: 18px;
        height: 18px;
        border: 2px solid #00c6ff;
        border-radius: 4px;
        background-color: transparent;
        cursor: pointer;
        position: relative;
        transition: all 0.3s ease;
        }

        .custom-checkbox input:checked {
        background-color: #00c6ff;
        }

        .custom-checkbox input:checked::after {
        content: '✔';
        font-size: 12px;
        color: #fff;
        position: absolute;
        left: 2px;
        top: -2px;
        }
        
    </style>
    <main class="main-content mt-0" style="overflow: hidden; min-height: 100vh; 
             background-image: url('assets/img/gplay.png'); 
             background-color: #000e26;
             background-position: center; 
             background-repeat: repeat;
             background-attachment: fixed;">
        <section>
            <div class="page-header min-vh-100 d-flex align-items-center overflow-hidden">
                <div class="container">
                    <div class="d-flex justify-content-center mb-4">
                        <img src="{{ asset('assets/img/logo.png') }}" 
                             style="height:8rem; filter: drop-shadow(0px 4px 8px rgba(0,0,0,0.6)); transition: transform 0.3s;" 
                             alt="Logo" class="logo-hover">
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-xl-6 col-md-6">
                            <div class="card glass-card p-4 border-0 animate__animated animate__fadeInUp">
                                <div class="card-header pb-0 text-center bg-transparent">
                                    <h3 class="fw-bold text-white display-6 mb-0">Monitoring App</h3>
                                    <p class="text-light mt-2">Silahkan Masukkan Data Login</p>
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
                                            <label for="username" class="form-label text-white">Username</label>
                                            <input type="text" id="username" name="username"
                                                class="form-control rounded-pill glass-input" 
                                                placeholder="Masukkan username"
                                                value="{{ old('username') }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="password" class="form-label text-white">Password</label>
                                            <div class="form-group password-container position-relative">
                                                <input type="password" id="password" name="password" 
                                                    class="form-control rounded-pill glass-input" 
                                                    placeholder="Masukkan password" required>
                                                <i class="eye-icon bi bi-eye text-white" id="togglePassword"></i>
                                            </div>
                                        </div>
                                        <label class="custom-checkbox">
                                            <input type="checkbox" id="remember" name="remember">
                                            Remember Me
                                        </label>
                                        <div class="text-center">
                                            <button type="submit" 
                                                class="btn btn-gradient w-100 mt-4 mb-3 rounded-pill fw-bold">
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