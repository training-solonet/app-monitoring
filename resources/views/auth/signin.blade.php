<x-guest-layout>
    <main class="main-content mt-0 bg-dark" style="overflow: hidden; min-height: 100vh;">
        <section>
            <div class="page-header min-vh-100 d-flex align-items-center overflow-hidden">
                <div class="container-fluid">
                    <div class="d-flex justify-content-center mb-3">
                        <img src="http://absensi.connectis.my.id/logo.png" style="height:8rem" alt="Logo">
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-xl-4 col-md-6">
                            <div class="card p-1 rounded-3 shadow-lg border">
                                <div class="card-header pb-0 text-center bg-transparent">
                                    <h3 class="font-weight-black text-dark display-6">Monitoring App</h3>
                                </div>
                                <div class="text-center">
                                    @if (session('status'))
                                        <div class="mb-4 font-medium text-sm text-green-600">
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
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" id="username" name="username"
                                                class="form-control" placeholder="Enter your username"
                                                value="{{ old('username') }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" id="password" name="password"
                                                class="form-control" placeholder="Enter password" required>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-dark w-100 mt-4 mb-3">Sign in</button>
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
