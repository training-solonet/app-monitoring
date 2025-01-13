<?php

namespace App\Http\Controllers;

use App\Models\Aktivitas;
use App\Models\Materi;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaRplController extends Controller
{
    public function index(Request $request)
    {
        // : Menyimpan status filter yang dipilih oleh pengguna pada request, digunakan untuk menyaring data berdasarkan status aktivitas siswa. Jika tidak ada filter, nilai default adalah 'all' (semua status).
        $statusFilterrpl = $request->get('status', 'all');
        // Menyimpan kategori filter yang dipilih oleh pengguna pada request, digunakan untuk menyaring data berdasarkan kategori aktivitas siswa. Nilai default adalah 'all'.
        $kategoriFilter = $request->get('kategori', 'all');
        // Menyimpan tanggal mulai dan tanggal selesai yang diterima dari input pengguna pada request, digunakan untuk memfilter data siswa berdasarkan tanggal pembuatan aktivitas.
        $tanggalMulai = $request->get('tanggal_mulai');
        $tanggalSelesai = $request->get('tanggal_selesai');
        //  Menyimpan ID pengguna yang sedang terautentikasi (menggunakan Auth::id()), digunakan untuk membatasi hasil query hanya pada siswa yang dimiliki oleh pengguna tersebut.
        $userId = Auth::id();

        // Query dasar
        $siswaQuery = Siswa::where('user_id', $userId);

        // Filter status
        if ($statusFilterrpl !== 'all') {
            $siswaQuery->where('status', $statusFilterrpl);
        }

        // Filter kategori
        if ($kategoriFilter !== 'all') {
            $siswaQuery->where('kategori', $kategoriFilter);
        }

        // Filter berdasarkan tanggal mulai dan selesai
        if ($tanggalMulai) {
            $siswaQuery->whereDate('created_at', '>=', $tanggalMulai);
        }
        if ($tanggalSelesai) {
            $siswaQuery->whereDate('created_at', '<=', $tanggalSelesai);
        }

        // Ambil data siswa dan proses total waktu
        $siswarpl = $siswaQuery->orderBy('created_at', 'desc')->get()->map(function ($item) {
            if ($item->waktu_mulai && $item->waktu_selesai) {
                $waktuMulai = Carbon::parse($item->waktu_mulai);
                $waktuSelesai = Carbon::parse($item->waktu_selesai);
                $item->total_waktu = $waktuSelesai->diff($waktuMulai)->format('%H:%I:%S');
            } else {
                $item->total_waktu = '-';
            }

            return $item;
        });

        // Data tambahan
        $aktivitasrpl = Aktivitas::all();
        $materirpl = Materi::where('jurusan', 'RPL')->get();

        // Return ke view
        return view('monitoring_siswa.siswarpl', compact('siswarpl', 'materirpl', 'aktivitasrpl', 'statusFilterrpl', 'kategoriFilter'));
    }

    public function updateTime(Request $request, $id)
    {
        // berisi data siswa yang diambil berdasarkan id dari parameter dan user_id yang terautentikasi melalui Auth::id(). 
        $item = Siswa::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // mewakili data yang dikirimkan oleh pengguna melalui HTTP request.
        $request->validate([
            'waktu_selesai' => 'required|date_format:H:i',
            'report' => 'required|string',
            'bukti' => 'nullable|array',
            'bukti.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        // menyimpan path file bukti yang di-upload. Jika ada file yang di-upload, path-nya disimpan di sini untuk disimpan dalam basis data.
        $filePath = null;
        if ($request->hasFile('bukti') && count($request->file('bukti')) === 1) {
            $file = $request->file('bukti')[0];
            $originalFileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('bukti', $originalFileName, 'public');
        } elseif ($request->hasFile('bukti') && count($request->file('bukti')) > 1) {
            $filePaths = [];
            foreach ($request->file('bukti') as $file) {
                $originalFileName = $file->getClientOriginalName();
                $filePaths[] = $file->storeAs('bukti', $originalFileName, 'public');
            }
            $filePath = implode(',', $filePaths);
        }

        // menyimpan tanggal dari waktu mulai aktivitas siswa yang diambil dengan menggunakan Carbon::parse($item->waktu_mulai)->format('Y-m-d'). Ini digunakan untuk memastikan waktu selesai memiliki tanggal yang sesuai dengan waktu mulai.
        $currentDate = Carbon::parse($item->waktu_mulai)->format('Y-m-d');
        // menggabungkan tanggal yang diperoleh dari $currentDate dengan waktu selesai yang dikirimkan oleh pengguna, membentuk format lengkap Y-m-d H:i untuk waktu selesai.
        $newWaktuSelesai = $currentDate . ' ' . $request->waktu_selesai;

        // digunakan untuk memperbarui data siswa dengan informasi yang baru, termasuk waktu selesai, status, laporan, dan bukti file.
        $item->update([
            'waktu_selesai' => $newWaktuSelesai,
            'status' => 'Selesai',
            'report' => $request->report,
            'bukti' => $filePath,
        ]);

        return redirect()->route('siswarpl.index')->with('success', 'Aktivitas Telah Diselesaikan');
    }

    public function storeMultiple(Request $request)
    {
        // mewakili data yang dikirimkan oleh pengguna melalui HTTP request. Dalam fungsi ini, digunakan untuk mengambil nilai kategori dan materi yang dipilih oleh pengguna.
        $request->validate([
            'kategori1' => 'required|in:Belajar,Projek,DiKantor,Keluar Dengan Teknisi',
            'materi_id1' => 'nullable|exists:materi,id',
            'kategori2' => 'nullable|in:Belajar,Projek,DiKantor,Keluar Dengan Teknisi',
            'materi_id2' => 'nullable|exists:materi,id',
        ]);

        // $kategori1, $kategori2: Variabel ini berisi kategori aktivitas yang dipilih oleh pengguna untuk dua entri siswa.
        Siswa::create([
            'kategori' => $request->kategori1,
            'materi_id' => $request->materi_id1,
            'status' => 'Mulai',
            'user_id' => Auth::id(),
        ]);

        // $materi_id1, $materi_id2: Variabel ini berisi ID materi yang dipilih oleh pengguna untuk dua entri siswa. Ini berfungsi untuk mengaitkan aktivitas dengan materi yang relevan.
        if ($request->kategori2 && $request->materi_id2) {
            // digunakan untuk membuat entri baru dalam tabel Siswa dengan data kategori, materi, status, dan ID pengguna yang terautentikasi.
            Siswa::create([
                'kategori' => $request->kategori2,
                'materi_id' => $request->materi_id2,
                'status' => 'Mulai',
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('siswarpl.index')->with('success', 'Laporan berhasil ditambahkan.');
    }

    public function start($id)
    {
        // berisi data siswa yang diambil berdasarkan ID yang diberikan dalam parameter. Fungsi ini akan memperbarui waktu mulai dan status siswa menjadi "Sedang Berlangsung".
        $siswarpl = Siswa::findOrFail($id);
        // Mengupdate waktu mulai dengan waktu saat ini (Carbon::now()).
        $siswarpl->waktu_mulai = Carbon::now();
        // Mengupdate status siswa menjadi "Sedang Berlangsung".
        $siswarpl->status = 'Sedang Berlangsung';
        $siswarpl->save();

        return redirect()->back()->with('success', 'Waktu mulai berhasil diupdate.');
    }

    public function stop($id)
    {
        // berisi data siswa yang diambil berdasarkan ID yang diberikan dalam parameter. Fungsi ini akan memperbarui waktu selesai dan status siswa menjadi "Selesai".
        $siswarpl = Siswa::findOrFail($id);
        // Mengupdate waktu selesai dengan waktu saat ini (Carbon::now()).
        $siswarpl->waktu_selesai = Carbon::now();
        // Mengupdate status siswa menjadi "Selesai".
        $siswarpl->status = 'Selesai';
        $siswarpl->save();

        return redirect()->back()->with('success', 'Waktu berhenti berhasil diupdate.');
    }

    public function toggle($id)
    {
        // berisi data siswa yang diambil berdasarkan ID yang diberikan dalam parameter. Fungsi ini digunakan untuk mengubah status siswa, apakah sedang berlangsung atau selesai, tergantung pada status saat ini.
        $siswarpl = Siswa::findOrFail($id);

        //  status akan diperbarui menjadi "Sedang Berlangsung" jika statusnya kosong atau "Sedang Berlangsung", dan menjadi "Selesai" jika statusnya "Sedang Berlangsung".
        if ($siswarpl->status === '') {
            // Bergantung pada status, variabel ini akan diupdate dengan waktu mulai atau selesai saat ini.
            $siswarpl->waktu_mulai = Carbon::now();
            $siswarpl->status = 'Sedang Berlangsung';
        } elseif ($siswarpl->status === 'Sedang Berlangsung') {
            $siswarpl->waktu_selesai = Carbon::now();
            $siswarpl->status = 'Selesai';
        }

        $siswarpl->save();

        return redirect()->back()->with('success', 'Status berhasil diperbarui.');
    }

    public function update(Request $request, $id)
    {
        // berisi data siswa yang diambil berdasarkan ID yang diberikan dalam parameter dan user_id yang terautentikasi. Fungsi ini akan mengupdate laporan dan bukti yang terkait dengan siswa.
        $siswa = Siswa::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'report' => 'required|string',
            'bukti' => 'nullable|array',
            'bukti.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'aktivitas_id1' => 'nullable|exists:aktivitas,id',
        ]);

        // Menyimpan path file bukti yang di-upload. Jika ada file yang di-upload, path-nya disimpan dalam variabel ini untuk disimpan dalam basis data.
        $filePath = null;
        if ($request->hasFile('bukti') && count($request->file('bukti')) === 1) {
            $file = $request->file('bukti')[0];
            $originalFileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('bukti', $originalFileName, 'public');
        } elseif ($request->hasFile('bukti') && count($request->file('bukti')) > 1) {
            $filePaths = [];
            foreach ($request->file('bukti') as $file) {
                $originalFileName = $file->getClientOriginalName();
                $filePaths[] = $file->storeAs('bukti', $originalFileName, 'public');
            }
            $filePath = implode(',', $filePaths);
        }

        //  Digunakan untuk memperbarui data siswa dengan informasi yang baru, termasuk laporan, bukti file, dan aktivitas yang terkait.
        $siswa->update([
            'report' => $request->report,
            'bukti' => $filePath,
            'aktivitas_id' => $request->aktivitas_id1,
        ]);

        return redirect()->route('siswarpl.index')->with('success', 'Data siswa berhasil diperbarui.');
    }
}
