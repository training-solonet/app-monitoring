<?php

namespace App\Http\Controllers;

use App\Models\Aktivitas;
use App\Models\Materi;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        //  Menyimpan nilai filter untuk status siswa yang dipilih dari parameter status dalam request.
        $statusFilter = $request->get('status', 'all');
        // Menyimpan nilai filter untuk kategori siswa yang dipilih dari parameter kategori dalam request.
        $kategoriFilter = $request->get('kategori', 'all');
        // Menyimpan nilai tanggal mulai yang dimasukkan dalam request untuk memfilter data siswa.
        $tanggalMulai = $request->get('tanggal_mulai');
        //  Menyimpan nilai tanggal selesai yang dimasukkan dalam request untuk memfilter data siswa.
        $tanggalSelesai = $request->get('tanggal_selesai');
        // Menyimpan ID pengguna yang saat ini sedang login (menggunakan Auth::id()).
        $userId = Auth::id();

        // Query dasar untuk data siswa
        $siswaQuery = Siswa::where('user_id', $userId);

        // Filter status jika tidak "all"
        if ($statusFilter !== 'all') {
            $siswaQuery->where('status', $statusFilter);
        }

        // Filter kategori jika tidak "all"
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

        // Proses data siswa dan hitung total waktu
        $siswa = $siswaQuery->orderBy('created_at', 'desc')->get()->map(function ($item) {
            if ($item->waktu_mulai && $item->waktu_selesai) {
                $waktuMulai = Carbon::parse($item->waktu_mulai);
                $waktuSelesai = Carbon::parse($item->waktu_selesai);
                $item->total_waktu = $waktuSelesai->diff($waktuMulai)->format('%H:%I:%S');
            } else {
                $item->total_waktu = '-';
            }

            return $item;
        });

        //mengambil seluruh tabel data aktivitas
        $aktivitas = Aktivitas::all();
        $materitkj = Materi::where('jurusan', 'TKJ')->get();

        return view('monitoring_siswa.siswa', compact('siswa', 'materitkj', 'aktivitas', 'statusFilter', 'kategoriFilter'));
    }

    public function updateTime(Request $request, $id)
    {
        // Mendapatkan objek Siswa berdasarkan id yang diberikan dan user_id yang terautentikasi (menggunakan Auth::id()).
        $item = Siswa::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'report' => 'required|string',
            'bukti' => 'nullable|array',
            'bukti.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        // menyimpan lokasi file yang di-upload. Ini bisa menyimpan path dari satu atau lebih file gambar yang diunggah oleh pengguna.
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

        //  Menyimpan waktu saat ini dalam format Y-m-d H:i, yang digunakan untuk mengupdate waktu selesai aktivitas siswa.
        $newWaktuSelesai = now()->format('Y-m-d H:i');

        $item->update([
            'waktu_selesai' => $newWaktuSelesai,
            'status' => 'Selesai',
            'aktivitas_id' => $request->aktivitas_id, //Menyimpan ID aktivitas yang dipilih dalam request, yang akan disimpan dalam database untuk menunjukkan aktivitas yang terkait dengan siswa.
            'report' => $request->report,
            'bukti' => $filePath,
        ]);

        return redirect()->route('siswa.index')->with('success', 'Aktivitas Telah Diselesaikan');
    }

    public function storeMultiple(Request $request)
    {
        // Menyimpan kategori aktivitas siswa, dengan nilai yang dibatasi pada pilihan yang sudah ditentukan ('Belajar', 'Projek', 'DiKantor', 'Keluar Dengan Teknisi').
        $request->validate([
            'kategori1' => 'required|in:Belajar,Projek,DiKantor,Keluar Dengan Teknisi',
            'materi_id1' => 'nullable|exists:materi,id',
            'kategori2' => 'nullable|in:Belajar,Projek,DiKantor,Keluar Dengan Teknisi',
            'materi_id2' => 'nullable|exists:materi,id',
        ]);

        Siswa::create([
            'kategori' => $request->kategori1,
            'materi_id' => $request->materi_id1,
            'status' => 'Mulai',
            'user_id' => Auth::id(),
        ]);

        if ($request->filled('kategori2') && $request->filled('materi_id2')) {
            Siswa::create([
                'kategori' => $request->kategori2,
                'materi_id' => $request->materi_id2,
                'status' => 'Mulai',
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('siswa.index')->with('success', 'Laporan berhasil ditambahkan.');
    }

    public function updateAndCreate(Request $request, $id)
    {
        // Mendapatkan objek Siswa berdasarkan id yang diberikan dan user_id yang terautentikasi.
        $item = Siswa::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'report' => 'required|string',
            'bukti' => 'nullable|array',
            'bukti.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        // digunakan untuk menyimpan path file yang di-upload.
        $filePath = null;
        if ($request->hasFile('bukti')) {
            $filePaths = [];
            foreach ($request->file('bukti') as $file) {
                $originalFileName = $file->getClientOriginalName();
                $filePaths[] = $file->storeAs('bukti', $originalFileName, 'public');
            }
            $filePath = implode(',', $filePaths);
        }

        $item->update([
            'waktu_selesai' => now(),
            'status' => 'selesai',
            'report' => $request->report,
            'bukti' => $filePath,
        ]);

        Siswa::create([
            'kategori' => 'Keluar Dengan Teknisi',
            'status' => 'Sedang Berlangsung',
            'user_id' => Auth::id(),
            'waktu_mulai' => now(),
        ]);

        return redirect()->route('siswa.index')->with('success', 'Data diperbarui dan entri baru berhasil ditambahkan.');
    }

    public function start($id)
    {
        //  Mendapatkan objek Siswa berdasarkan id yang diberikan dan user_id yang terautentikasi. Variabel ini digunakan untuk menyimpan waktu mulai aktivitas siswa dan memperbarui status menjadi "Sedang Berlangsung".
        $siswa = Siswa::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $siswa->waktu_mulai = Carbon::now();
        $siswa->status = 'Sedang Berlangsung';
        $siswa->save();

        return redirect()->back()->with('success', 'Waktu mulai berhasil diupdate.');
    }

    public function stop($id)
    {
        // Mendapatkan objek Siswa berdasarkan id yang diberikan dan user_id yang terautentikasi. Variabel ini digunakan untuk menyimpan waktu selesai aktivitas siswa dan memperbarui status menjadi "Selesai".
        $siswa = Siswa::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $siswa->waktu_selesai = Carbon::now();
        $siswa->status = 'selesai';
        $siswa->save();

        return redirect()->back()->with('success', 'Waktu berhenti berhasil diupdate.');
    }

    public function toggle($id)
    {
        // Mendapatkan objek Siswa berdasarkan id yang diberikan dan user_id yang terautentikasi.
        $siswa = Siswa::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        //  Mengecek status siswa (apakah "Mulai","Sedang Berlangsung","Selesai") dan memperbarui waktu mulai atau selesai sesuai dengan perubahan status yang diperlukan.
        if ($siswa->status === 'Mulai') {
            $siswa->waktu_mulai = Carbon::now();
            $siswa->status = 'Sedang Berlangsung';
        } elseif ($siswa->status === 'Sedang Berlangsung') {
            $siswa->waktu_selesai = Carbon::now();
            $siswa->status = 'Selesai';
        }

        $siswa->save();

        return redirect()->back()->with('success', 'Status berhasil diperbarui.');
    }

    public function update(Request $request, $id)
    {
        //  Mendapatkan objek Siswa berdasarkan id yang diberikan dan user_id yang terautentikasi. Variabel ini digunakan untuk memperbarui data siswa.
        $siswa = Siswa::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'report' => 'required|string',
            'bukti' => 'nullable|array',
            'bukti.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'aktivitas_id1' => 'nullable|exists:aktivitas,id',

        ]);

        // Menyimpan path file yang di-upload yang terkait dengan bukti aktivitas siswa
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

        $siswa->update([
            'report' => $request->report,
            'bukti' => $filePath,
            'aktivitas_id' => $request->aktivitas_id1,
        ]);

        return redirect()->route('siswa.index')->with('success', 'Laporan berhasil diperbarui.');
    }
}
