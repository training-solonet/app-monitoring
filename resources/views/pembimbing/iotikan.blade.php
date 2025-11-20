<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />

    <style>
        /* Insert your existing styles here */
    </style>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5 container-main">

            <!-- Page Header -->
            <div class="page-header">
                <h1>Pengaturan IoT Ikan</h1>
                <p>Kelola jaringan dan jadwal pemberian pakan ikan.</p>
            </div>

            <!-- NETWORK SECTION -->
            <div class="card">
                <div class="card-header">
                    <h5><i class="fa-solid fa-wifi"></i> Network Configuration</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('network.update') }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">SSID</label>
                                <input type="text" class="form-control" name="ssid" value="{{ $network->ssid ?? '' }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Password</label>
                                <input type="text" class="form-control" name="password" value="{{ $network->password ?? '' }}" required>
                            </div>
                        </div>
                        <button class="btn-primary-custom" type="submit"><i class="fa-solid fa-save"></i> Simpan</button>
                    </form>
                </div>
            </div>

            <!-- IOTIKAN SECTION -->
            <div class="card">
                <div class="card-header">
                    <h5><i class="fa-solid fa-fish"></i> Jadwal Pemberian Makan</h5>
                </div>
                <div class="card-body">
                    <button class="btn-primary-custom mb-3" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
                        <i class="fa-solid fa-plus"></i> Tambah Jadwal
                    </button>

                    <div class="table-responsive">
                        <table id="scheduleTable" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Interval (ms)</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($iotikans as $item)
                                <tr>
                                    <td>{{ $item->time }}</td>
                                    <td>{{ $item->interval }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-success-custom" data-bs-toggle="modal" data-bs-target="#editScheduleModal{{ $item->id }}"><i class="fa-solid fa-pen"></i></button>
                                            <form action="{{ route('iotikan.destroy', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn-danger-custom" onclick="return confirm('Hapus jadwal ini?')"><i class="fa-solid fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <x-app.footer />
    </main>

    <!-- MODAL: ADD SCHEDULE -->
    <div class="modal fade" id="addScheduleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Jadwal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('iotikan.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Waktu</label>
                            <input type="time" class="form-control" name="schedule" required>
                        </div>
                        <div class="form-group mt-3">
                            <label class="form-label">Interval Servo (ms)</label>
                            <input type="number" class="form-control" name="interval" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn-primary-custom">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: EDIT SCHEDULE -->
    @foreach ($iotikans as $item)
    <div class="modal fade" id="editScheduleModal{{ $item->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Jadwal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('iotikan.update', $item->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Waktu</label>
                            <input type="time" class="form-control" name="schedule" value="{{ $item->time }}" required>
                        </div>
                        <div class="form-group mt-3">
                            <label class="form-label">Interval Servo (ms)</label>
                            <input type="number" class="form-control" name="interval" value="{{ $item->interval }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn-primary-custom">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <!-- SCRIPTS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#scheduleTable').DataTable();
        });
    </script>

</x-app-layout>
