<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />

    <style>
        body {
            background: #f4f7fa;
        }
        .page-header h1 {
            font-weight: 700;
            margin-bottom: 20px;
        }
        .page-header p {
            color: #6c757d;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border: none;
            margin-bottom: 25px;
        }
        .card-header {
            background: #fff;
            padding: 20px;
            border-bottom: 1px solid #dee2e6;
            border-radius: 15px 15px 0 0;
        }
        .card-header h5 {
            margin: 0;
            font-weight: 600;
        }
        .btn-primary-custom {
            background: #3b82f6;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            color: #fff;
            transition: 0.2s;
        }
        .btn-primary-custom:hover {
            background: #1e40af;
        }
        .btn-success-custom {
            background: #16a34a;
            border: none;
            padding: 8px 12px;
            border-radius: 8px;
            color: #fff;
        }
        .btn-danger-custom {
            background: #dc2626;
            border: none;
            padding: 8px 12px;
            border-radius: 8px;
            color: #fff;
        }
        .form-group label {
            font-weight: 600;
        }
        .action-buttons button {
            margin-right: 5px;
        }
        .modal-content {
            border-radius: 15px;
        }
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }
        #logsTable th:nth-child(1),
        #logsTable td:nth-child(1) {
            width: 20px !important;
            white-space: nowrap;
        }
        #logsTable th:nth-child(2),
        #logsTable td:nth-child(2) {
            width: 120px !important;
            white-space: nowrap;
        }

    </style>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5 container-main">

            <!-- Page Header -->
            <div class="page-header">
                <h1>Pengaturan IoT Ikan</h1>
            </div>

            <!-- LOGS SECTION -->
            <div class="card">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fa-solid fa-database fa-lg"></i>
                    <h5>Logs IoT</h5>
                </div>
                <div class="card-body">

                    <!-- FILTER LOGS -->
                    <form method="GET" action="{{ route('iotikan') }}" class="mb-3">
                        <div class="row g-3">

                            <div class="col-md-4">
                                <label class="form-label">Dari Tanggal</label>
                                <input type="date" name="start" class="form-control" value="{{ request('start') }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Sampai Tanggal</label>
                                <input type="date" name="end" class="form-control" value="{{ request('end') }}">
                            </div>

                            <div class="col-md-4 d-flex align-items-end gap-2">
                                <button class="btn btn-primary-custom" type="submit">
                                    <i class="fa-solid fa-filter"></i> Filter
                                </button>

                                <a href="{{ route('iotikan') }}" class="btn btn-secondary">
                                    <i class="fa-solid fa-list"></i> Tampilkan Semua
                                </a>
                            </div>

                        </div>
                    </form>

                    <div class="table-responsive">
                        <table id="logsTable" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Waktu</th>
                                    <th>Pesan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logs as $log)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $log->created_at }}</td>
                                    <td>{{ $log->log_message }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>


            <!-- IOTIKAN SECTION -->
            <div class="card">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fa-solid fa-fish fa-lg"></i>
                    <h5>Jadwal Pemberian Makan</h5>
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
                                            <form action="{{ route('iotikan.destroy', $item->id) }}" method="POST" class="d-inline">
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
                        <label class="form-label">Waktu</label>
                        <input type="time" class="form-control" name="schedule" required>

                        <label class="form-label mt-3">Interval Servo (ms)</label>
                        <input type="number" class="form-control" name="interval" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
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
                    @method('POST')
                    <div class="modal-body">
                        <label class="form-label">Waktu</label>
                        <input type="time" class="form-control" name="schedule" value="{{ $item->time }}" required>

                        <label class="form-label mt-3">Interval Servo (ms)</label>
                        <input type="number" class="form-control" name="interval" value="{{ $item->interval }}" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#scheduleTable').DataTable({
                pageLength: 10
            });

            $('#logsTable').DataTable({
                pageLength: 10
            });
        });
    </script>

</x-app-layout>
