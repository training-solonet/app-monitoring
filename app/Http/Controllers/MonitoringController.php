<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\Siswa;
use App\Models\User;
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

        // Query monitoring siswa
        $monitoring = Siswa::query();

        // Filter berdasarkan nama siswa
        if ($nama_siswa) {
            $monitoring->whereHas('siswa_monitoring', function ($query) use ($nama_siswa) {
                $query->where('username', 'like', "%$nama_siswa%");
            });
        }

        // Filter berdasarkan status
        if ($status) {
            $monitoring->where('status', $status);
        }

        // Filter berdasarkan jurusan
        if ($jurusan) {
            $monitoring->whereHas('siswa_monitoring', function ($query) use ($jurusan) {
                $query->where('jurusan', $jurusan);
            });
        }

        // Filter berdasarkan tanggal mulai
        if ($tanggal_mulai) {
            $monitoring->whereDate('waktu_mulai', '>=', $tanggal_mulai);
        }

        // Filter berdasarkan tanggal selesai
        if ($tanggal_selesai) {
            $monitoring->whereDate('waktu_selesai', '<=', $tanggal_selesai);
        }

        // Ambil daftar siswa dan materi untuk dropdown/filtering
        $siswa_monitoring = User::where('role', 'siswa')->get();
        $materi_monitoring = Materi::all();

        // Urutkan data berdasarkan created_at terbaru dan paginasi
        $monitoring = $monitoring->orderBy('created_at', 'desc')->paginate(10);

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
