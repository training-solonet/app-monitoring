<nav class="navbar navbar-main navbar-expand-lg mx-5 px-0 shadow-none rounded" id="navbarBlur" navbar-scroll="false">
    <div class="container-fluid py-1 px-2">
        <nav aria-label="breadcrumb">
            <img src="https://absensi.connectis.my.id/skote/assets/images/connectislg.png" height="50px">
            {{-- <ol class="breadcrumb bg-transparent mb-1 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Dashboard</a></li>
                <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Dashboard</li>
            </ol>
            <h6 class="font-weight-bold mb-0">Dashboard</h6> --}}
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
    <div class="d-flex align-items-center justify-content-end w-100 gap-2">
        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-sm btn-white mb-0 me-1" type="submit">Log out</button>
        </form>

        <!-- Sidebar Button / Hamburger (hanya untuk layar kecil) -->
        <li class="nav-item d-xl-none d-flex align-items-center">
            <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                </div>
            </a>
        </li>
    </div>
</div>

    </div>
</nav>
<!-- End Navbar -->
