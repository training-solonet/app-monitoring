<?php

namespace App\Http\Controllers;

use App\Models\materi;
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
        $search = $request->input('search');

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

        if ($search) {
            $monitoring->where(function ($query) use ($search) {
                $query->where('kategori', 'like', '%'.$search.'%')
                    ->orWhere('report', 'like', '%'.$search.'%')
                    ->orWhere('waktu_mulai', 'like', '%'.$search.'%')
                    ->orWhere('waktu_selesai', 'like', '%'.$search.'%')
                    ->orWhere('status', 'like', '%'.$search.'%')
                    ->orWhereHas('siswa_monitoring', function ($subQuery) use ($search) {
                        $subQuery->where('username', 'like', '%'.$search.'%')
                            ->orWhere('jurusan', 'like', '%'.$search.'%');
                    })
                    ->orWhereHas('materitkj', function ($subQuery) use ($search) {
                        $subQuery->where('materi', 'like', '%'.$search.'%');
                    });
            });
        }

        $siswa_monitoring = User::all();
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
