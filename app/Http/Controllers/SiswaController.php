<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Materi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->get('status', 'all');
        $userId = Auth::id();

        if ($statusFilter === 'all') {
            $siswa = Siswa::where('user_id', $userId)->get();
        } else {
            $siswa = Siswa::where('user_id', $userId)
                          ->where('status', $statusFilter)
                          ->get();
        }

        $siswa = $siswa->map(function ($item) {
            if ($item->waktu_mulai && $item->waktu_selesai) {
                $waktuMulai = Carbon::parse($item->waktu_mulai);
                $waktuSelesai = Carbon::parse($item->waktu_selesai);
                $item->total_waktu = $waktuSelesai->diff($waktuMulai)->format('%H:%I:%S');
            } else {
                $item->total_waktu = '-';
            }
            return $item;
        });

        $siswa_monitoring = User::all();
        $materitkj = Materi::all();
        return view('monitoring_siswa.siswa', compact('siswa', 'materitkj','siswa_monitoring'));
    }

    public function updateTime(Request $request, $id)
    {
        $item = Siswa::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'waktu_selesai' => 'required|date_format:H:i', 
            'report' => 'required|string'
        ]);

        $currentDate = Carbon::parse($item->waktu_mulai)->format('Y-m-d');
        $newWaktuSelesai = $currentDate . ' ' . $request->waktu_selesai;

        $item->update([
            'waktu_selesai' => $newWaktuSelesai,
            'status' => 'done',
            'report' => $request->report,
        ]);

        return redirect()->route('siswa.index')->with('success', 'Aktivitas Telah Diselesaikan');
    }

    public function storeMultiple(Request $request)
    {
        $request->validate([
            'kategori1' => 'required|in:Learning,Project,DiKantor,Keluar Dengan Teknisi',
            'materi_id1' => 'nullable|exists:materi,id',
            'kategori2' => 'nullable|in:Learning,Project,DiKantor,Keluar Dengan Teknisi',
            'materi_id2' => 'nullable|exists:materi,id'
        ]);

        Siswa::create([
            'kategori' => $request->kategori1,
            'materi_id' => $request->materi_id1,
            'status' => 'to do',
            'user_id' => Auth::id(),
        ]);

        if ($request->filled('kategori2') && $request->filled('materi_id2')) {
            Siswa::create([
                'kategori' => $request->kategori2,
                'materi_id' => $request->materi_id2,
                'status' => 'to do',
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('siswa.index')->with('success', 'Laporan berhasil ditambahkan.');
    }

    public function start($id)
    {
        $siswa = Siswa::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $siswa->waktu_mulai = Carbon::now();
        $siswa->status = 'doing';
        $siswa->save();

        return redirect()->back()->with('success', 'Waktu mulai berhasil diupdate.');
    }

    public function stop($id)
    {
        $siswa = Siswa::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $siswa->waktu_selesai = Carbon::now();
        $siswa->status = 'done';
        $siswa->save();

        return redirect()->back()->with('success', 'Waktu berhenti berhasil diupdate.');
    }

    public function toggle($id)
    {
        $siswa = Siswa::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($siswa->status === 'to do') {
            $siswa->waktu_mulai = Carbon::now();
            $siswa->status = 'doing';
        } elseif ($siswa->status === 'doing') {
            $siswa->waktu_selesai = Carbon::now();
            $siswa->status = 'done';
        }

        $siswa->save();

        return redirect()->back()->with('success', 'Status berhasil diperbarui.');
    }
}
