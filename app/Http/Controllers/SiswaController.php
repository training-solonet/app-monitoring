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
        $statusFilter = $request->get('status', 'all');
        $kategoriFilter = $request->get('kategori', 'all'); // Default 'all'
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

        // Proses data siswa dan hitung total waktu
        $siswa = $siswaQuery->orderBy('created_at', 'desc')->get()->map(function ($item) {
            if ($item->waktu_mulai && $item->waktu_selesai) {
                $waktuMulai = Carbon::parse($item->waktu_mulai);
                $waktuSelesai = Carbon::parse($item->waktu_selesai);
                $item->total_waktu = $waktuSelesai->diff($waktuMulai)->format('%H:%I:%S');
            } else {
                $item->total_waktu = '-';
            }
            \Log::info("Item ID: {$item->id}, Waktu Mulai: {$item->waktu_mulai}");

            return $item;
        });

        $aktivitas = Aktivitas::all();
        $materitkj = Materi::where('jurusan', 'TKJ')->get();

        return view('monitoring_siswa.siswa', compact('siswa', 'materitkj', 'aktivitas', 'statusFilter', 'kategoriFilter'));
    }

    public function updateTime(Request $request, $id)
    {
        $item = Siswa::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'report' => 'required|string',
            'bukti' => 'nullable|array',
            'bukti.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);

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

        $newWaktuSelesai = now()->format('Y-m-d H:i');

        $item->update([
            'waktu_selesai' => $newWaktuSelesai,
            'status' => 'Selesai',
            'aktivitas_id' => $request->aktivitas_id,
            'report' => $request->report,
            'bukti' => $filePath,
        ]);

        return redirect()->route('siswa.index')->with('success', 'Aktivitas Telah Diselesaikan');
    }

    public function storeMultiple(Request $request)
    {
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
        $item = Siswa::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'report' => 'required|string',
            'bukti' => 'nullable|array',
            'bukti.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);

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
        $siswa = Siswa::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $siswa->waktu_mulai = Carbon::now();
        $siswa->status = 'Sedang Berlangsung';
        $siswa->save();

        return redirect()->back()->with('success', 'Waktu mulai berhasil diupdate.');
    }

    public function stop($id)
    {
        $siswa = Siswa::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $siswa->waktu_selesai = Carbon::now();
        $siswa->status = 'selesai';
        $siswa->save();

        return redirect()->back()->with('success', 'Waktu berhenti berhasil diupdate.');
    }

    public function toggle($id)
    {
        $siswa = Siswa::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

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
        $siswa = Siswa::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'report' => 'required|string',
            'bukti' => 'nullable|array',
            'bukti.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'aktivitas_id1' => 'nullable|exists:aktivitas,id',

        ]);

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
