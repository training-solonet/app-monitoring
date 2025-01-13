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
        // menyimpan nilai input dari pengguna untuk mencari siswa berdasarkan nama (username).
        $nama_siswa = $request->input('nama_siswa');
        // menyimpan status yang dipilih oleh pengguna untuk memfilter data berdasarkan status.
        $status = $request->input('status');
        //  menyimpan nilai jurusan yang dipilih oleh pengguna untuk mencari data siswa berdasarkan jurusan.
        $jurusan = $request->input('jurusan');
        // menyimpan tanggal mulai yang dipilih pengguna untuk mencari data monitoring yang dimulai setelah tanggal tersebut.
        $tanggal_mulai = $request->input('tanggal_mulai');
        // menyimpan tanggal selesai yang dipilih pengguna untuk mencari data monitoring yang selesai sebelum tanggal tersebut.
        $tanggal_selesai = $request->input('tanggal_selesai');

        // menyimpan objek query builder yang digunakan untuk mendapatkan data monitoring siswa berdasarkan kriteria pencarian yang diberikan oleh pengguna.
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

        // menyimpan semua data User dengan role siswa.
        $siswa_monitoring = User::where('role', 'siswa')->get();
        // menyimpan semua data materi yang dapat diakses dalam proses monitoring.
        $materi_monitoring = Materi::all();
        $monitoring = $monitoring->get();

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
