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
        $statusFilterrpl = $request->get('status', 'all');
        $kategoriFilter = $request->get('kategori', 'all'); // Default 'all'
        $userId = Auth::id();

        $siswaQuery = Siswa::where('user_id', $userId);

        if ($statusFilterrpl !== 'all') {
            $siswaQuery->where('status', $statusFilterrpl);
        }

        // Filter kategori jika tidak "all"
        if ($kategoriFilter !== 'all') {
            $siswaQuery->where('kategori', $kategoriFilter);
        }

        $siswarpl = $siswaQuery->orderBy('created_at', 'desc')->get()->map(function ($item) {
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

        $aktivitasrpl = Aktivitas::all();
        $materirpl = Materi::where('jurusan', 'RPL')->get();

        return view('monitoring_siswa.siswarpl', compact('siswarpl', 'materirpl', 'aktivitasrpl', 'statusFilterrpl', 'kategoriFilter'));
    }

    public function updateTime(Request $request, $id)
    {
        $item = Siswa::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'waktu_selesai' => 'required|date_format:H:i',
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

        $currentDate = Carbon::parse($item->waktu_mulai)->format('Y-m-d');
        $newWaktuSelesai = $currentDate.' '.$request->waktu_selesai;

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

        if ($request->kategori2 && $request->materi_id2) {
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
        $siswarpl = Siswa::findOrFail($id);
        $siswarpl->waktu_mulai = Carbon::now();
        $siswarpl->status = 'Sedang Berlangsung';
        $siswarpl->save();

        return redirect()->back()->with('success', 'Waktu mulai berhasil diupdate.');
    }

    public function stop($id)
    {
        $siswarpl = Siswa::findOrFail($id);
        $siswarpl->waktu_selesai = Carbon::now();
        $siswarpl->status = 'Selesai';
        $siswarpl->save();

        return redirect()->back()->with('success', 'Waktu berhenti berhasil diupdate.');
    }

    public function toggle($id)
    {
        $siswarpl = Siswa::findOrFail($id);

        if ($siswarpl->status === 'Mulai') {
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

        return redirect()->route('siswarpl.index')->with('success', 'Data siswa berhasil diperbarui.');
    }
}
