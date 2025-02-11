<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;


class MonitoringController extends Controller
{
    public function index(Request $request)
{
    $nama_siswa = $request->input('nama_siswa');
    $status = $request->input('status');
    $jurusan = $request->input('jurusan');
    $tanggal_mulai = $request->input('tanggal_mulai');
    $tanggal_selesai = $request->input('tanggal_selesai');

    $monitoring = Siswa::query();

    if ($nama_siswa) {
        $monitoring->whereHas('siswa_monitoring', function ($query) use ($nama_siswa) {
            $query->where('username', $nama_siswa);
        });
    }

    if ($status) {
        $monitoring->where('status', $status);
    }

    if ($jurusan) {
        $monitoring->whereHas('siswa_monitoring', function ($query) use ($jurusan) {
            $query->where('jurusan', $jurusan);
        });
    }

    if ($tanggal_mulai) {
        $monitoring->whereDate('waktu_mulai', '>=', $tanggal_mulai);
    }

    if ($tanggal_selesai) {
        $monitoring->whereDate('waktu_selesai', '<=', $tanggal_selesai);
    }

    $siswa_monitoring = User::where('role', 'siswa')->get();
    $materi_monitoring = Materi::all();

    // Urutkan berdasarkan created_at terbaru
    $monitoring = $monitoring->orderBy('created_at', 'desc')->paginate(10)->through(function ($item) {
        if ($item->waktu_mulai) {
            $waktuMulai = Carbon::parse($item->waktu_mulai);
            $waktuSelesai = $item->waktu_selesai ? Carbon::parse($item->waktu_selesai) : Carbon::now();
    
            $totalMenit = $waktuMulai->diffInMinutes($waktuSelesai);
            $hari = intdiv($totalMenit, 1440);
            $sisaMenit = $totalMenit % 1440;
            $jam = intdiv($sisaMenit, 60);
            $menit = $sisaMenit % 60;
    
            $item->total_waktu = ($hari > 0 ? "{$hari} Hari " : "") . "{$jam} Jam {$menit} Menit";
        } else {
            $item->total_waktu = '-';
        }
    
        return $item;
    });
    

    return view('monitoring_siswa.monitoring', compact('monitoring', 'materi_monitoring', 'siswa_monitoring'));
}


    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);

        return view('monitoring_siswa.monitoring', compact('siswa'));
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->update($request->all());

        return redirect()->route('monitoring.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();

        return redirect()->route('monitoring.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}
