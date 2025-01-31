<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid px-5 py-4">
            <div class="mt-4 row">
                <div class="col-12">
                    <div class="card shadow-lg border-radius-lg">
                        <div class="card-header border-bottom pb-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="font-weight-bold text-lg mb-0">Data Monitoring Siswa</h6>
                            </div>
                            <hr>
                            <form id="filterForm" method="GET" action="{{ route('monitoring.index') }}">
                                <div class="row g-2 align-items-end">
                                    <div class="col-sm-3">
                                        <label for="nama_siswa" class="form-label">Nama Siswa</label>
                                        <select id="nama_siswa" name="nama_siswa" class="form-select"
                                            onchange="document.getElementById('filterForm').submit();">
                                            <option disabled selected>Pilih Siswa</option>
                                            @foreach ($siswa_monitoring as $siswa)
                                                <option value="{{ $siswa->username }}"
                                                    {{ request('nama_siswa') == $siswa->username ? 'selected' : '' }}>
                                                    {{ $siswa->username }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="status" class="form-label">Status</label>
                                        <select id="status" name="status" class="form-select"
                                            onchange="document.getElementById('filterForm').submit();">
                                            <option disabled selected>Pilih Status</option>
                                            <option value="Mulai" {{ request('status') == 'Mulai' ? 'selected' : '' }}>
                                                Mulai</option>
                                            <option value="Sedang Berlangsung"
                                                {{ request('status') == 'Sedang Berlangsung' ? 'selected' : '' }}>Sedang
                                                Berlangsung</option>
                                            <option value="Selesai"
                                                {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="jurusan" class="form-label">Jurusan</label>
                                        <select id="jurusan" name="jurusan" class="form-select"
                                            onchange="document.getElementById('filterForm').submit();">
                                            <option disabled selected>Pilih Jurusan</option>
                                            <option value="TKJ" {{ request('jurusan') == 'TKJ' ? 'selected' : '' }}>
                                                TKJ</option>
                                            <option value="RPL" {{ request('jurusan') == 'RPL' ? 'selected' : '' }}>
                                                RPL</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                                        <input type="date" id="tanggal_mulai" name="tanggal_mulai"
                                            class="form-control" value="{{ request('tanggal_mulai') }}"
                                            onchange="document.getElementById('filterForm').submit();">
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                                        <input type="date" id="tanggal_selesai" name="tanggal_selesai"
                                            class="form-control" value="{{ request('tanggal_selesai') }}"
                                            onchange="document.getElementById('filterForm').submit();">
                                    </div>
                                    <div class="col-sm-1">
                                        <a href="{{ route('monitoring.index') }}"
                                            class="btn btn-outline-secondary w-100 mb-1"> <i
                                                class="fa-solid fa-arrows-rotate"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>


                        <div class="card-body">
                            <div class="table-responsive p-0">
                                <table class="table table-custom">
                                    <thead class="">
                                        <tr>
                                            <th class="text-center text-xs font-weight-semibold opacity-7">No</th>
                                            <th class="text-center text-xs font-weight-semibold opacity-7">Nama Siswa
                                            </th>
                                            <th class="text-center text-xs font-weight-semibold opacity-7">Jurusan</th>
                                            <th class="text-center text-xs font-weight-semibold opacity-7">Kategori</th>
                                            <th class="text-center text-xs font-weight-semibold opacity-7">Detail</th>
                                            <th class="text-center text-xs font-weight-semibold opacity-7">Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody class="">
                                        @foreach ($monitoring as $index => $item)
                                            <tr>
                                                <td class="align-middle text-center">{{ $index + 1 }}</td>
                                                <td class="align-middle text-center">
                                                    {{ $item->siswa_monitoring?->username ?? '' }}</td>
                                                <td class="align-middle text-center">
                                                    {{ $item->siswa_monitoring?->jurusan ?? '' }}</td>
                                                <td class="align-middle text-center">{{ $item->kategori }}</td>
                                                <td class="align-middle text-center">
                                                    <a class="mb-0" data-bs-toggle="modal"
                                                        data-bs-target="#DetailModal{{ $item->id }}">
                                                        <i class="fa-solid fa-circle-info"></i>
                                                    </a>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <a href="#" class="text-danger"
                                                        onclick="confirmDelete({{ $item->id }})">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                    <form id="delete-form-{{ $item->id }}"
                                                        action="{{ route('monitoring.destroy', $item->id) }}"
                                                        method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </td>
                                    </tbody>

                                </table>
                            </div>
                        </div>

                        {{-- Modal Detail --}}
                        @foreach ($monitoring as $item)
                            <div class="modal fade" id="DetailModal{{ $item->id }}" tabindex="-1"
                                aria-labelledby="DetailModalLabel{{ $item->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-md"> <!-- Change modal-lg to modal-md -->
                                    <div class="modal-content shadow-lg rounded">
                                        <div class="modal-header bg-info text-white">
                                            <h5 class="modal-title text-white"
                                                id="DetailModalLabel{{ $item->id }}">Detail Monitoring</h5>
                                            <button type="button" class="btn-close text-light"
                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <ul class="list-group list-group-flush">
                                                        <li class="list-group-item"><strong>Materi:</strong>
                                                            {{ $item->materitkj?->materi ?? 'Tidak ada materi' }}</li>
                                                        <li class="list-group-item"><strong>Waktu Mulai:</strong>
                                                            {{ \Carbon\Carbon::parse($item->waktu_mulai)->locale('id')->translatedFormat('l, d M Y H:i') }}
                                                        </li>
                                                        <li class="list-group-item"><strong>Waktu Selesai:</strong>
                                                            {{ \Carbon\Carbon::parse($item->waktu_selesai)->locale('id')->translatedFormat('l, d M Y H:i') }}
                                                        </li>
                                                        <li class="list-group-item">
                                                            <strong>Status:</strong>
                                                            @php
                                                                $statusClass = match ($item->status) {
                                                                    'Selesai' => 'btn-success',
                                                                    'Belum Dimulai' => 'btn-secondary',
                                                                    'Sedang Berlangsung' => 'btn-info',
                                                                    default => 'btn-dark',
                                                                };
                                                            @endphp
                                                            <button
                                                                class="btn {{ $statusClass }} btn-sm mt-3">{{ $item->status }}</button>
                                                        </li>
                                                        <li class="list-group-item"><strong>Report:</strong>
                                                            <textarea class="form-control" rows="4" readonly>{{ $item->report }}</textarea>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="text-center">Bukti</h6>
                                                    @if ($item->bukti)
                                                        <div id="carouselBukti{{ $item->id }}"
                                                            class="carousel slide" data-bs-ride="carousel">
                                                            <div class="carousel-inner">
                                                                @foreach (explode(',', $item->bukti) as $index => $buktiPath)
                                                                    <div
                                                                        class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                                        <a href="{{ Storage::url($buktiPath) }}"
                                                                            target="_blank">
                                                                            <img id="img{{ $item->id }}-{{ $index }}"
                                                                                src="{{ Storage::url($buktiPath) }}"
                                                                                class="d-block w-100 rounded"
                                                                                alt="Bukti"
                                                                                style="max-height: 400px; object-fit: cover;">
                                                                        </a>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            <button id="prevBtn{{ $item->id }}"
                                                                class="carousel-control-prev" type="button"
                                                                data-bs-target="#carouselBukti{{ $item->id }}"
                                                                data-bs-slide="prev">
                                                                <span class="carousel-control-prev-icon"
                                                                    aria-hidden="true"></span>
                                                                <span class="visually-hidden">Previous</span>
                                                            </button>
                                                            <button id="nextBtn{{ $item->id }}"
                                                                class="carousel-control-next" type="button"
                                                                data-bs-target="#carouselBukti{{ $item->id }}"
                                                                data-bs-slide="next">
                                                                <span class="carousel-control-next-icon"
                                                                    aria-hidden="true"></span>
                                                                <span class="visually-hidden">Next</span>
                                                            </button>
                                                        </div>
                                                    @else
                                                        <p class="text-center text-muted">Bukti belum diunggah</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

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
    </main>

    <script>
        // Helper function to calculate the brightness of an image
        function getImageBrightness(image) {
            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');
            canvas.width = image.width;
            canvas.height = image.height;
            context.drawImage(image, 0, 0, image.width, image.height);

            const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
            const data = imageData.data;

            let r = 0,
                g = 0,
                b = 0;
            let total = data.length / 4;

            for (let i = 0; i < total; i++) {
                r += data[i * 4];
                g += data[i * 4 + 1];
                b += data[i * 4 + 2];
            }

            r = r / total;
            g = g / total;
            b = b / total;

            const brightness = (r * 0.2126 + g * 0.7152 + b * 0.0722); // Luminance calculation
            return brightness;
        }

        // Function to update the button colors based on brightness
        function updateButtonColors(itemId) {
            const image = document.querySelector(`#img${itemId}`);
            const prevButton = document.querySelector(`#prevBtn${itemId}`);
            const nextButton = document.querySelector(`#nextBtn${itemId}`);

            const brightness = getImageBrightness(image);

            // Check if image is dark or light
            if (brightness < 128) {
                // Dark image, make buttons light
                prevButton.style.backgroundColor = '#fff';
                nextButton.style.backgroundColor = '#fff';
                prevButton.querySelector('span').style.backgroundColor = '#000';
                nextButton.querySelector('span').style.backgroundColor = '#000';
            } else {
                // Light image, make buttons dark
                prevButton.style.backgroundColor = '#000';
                nextButton.style.backgroundColor = '#000';
                prevButton.querySelector('span').style.backgroundColor = '#fff';
                nextButton.querySelector('span').style.backgroundColor = '#fff';
            }
        }

        // Wait for the modal to be shown and then calculate brightness
        document.addEventListener('DOMContentLoaded', function() {
            @foreach ($monitoring as $item)
                const image = document.querySelector(
                `#img{{ $item->id }}-0`); // Grab the first image for each item
                if (image) {
                    updateButtonColors({{ $item->id }});
                }
            @endforeach
        });
    </script>
</x-app-layout>
