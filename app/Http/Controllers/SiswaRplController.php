<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use Carbon\Carbon;

class SiswaRplController extends Controller
{
    public function index()
    {
        $siswarpl = Siswa::all()->map(function ($item) {
            if ($item->waktu_mulai && $item->waktu_selesai) {
                $waktuMulai = Carbon::parse($item->waktu_mulai);
                $waktuSelesai = Carbon::parse($item->waktu_selesai);
                $item->total_waktu = $waktuSelesai->diff($waktuMulai)->format('%H:%I:%S'); // format HH:MM:SS
            } else {
                $item->total_waktu = '-';
            }
            return $item;
        });

        return view('monitoring_siswa.siswarpl', compact('siswarpl'));
    }


    public function storeMultiple(Request $request)
    {
        $request->validate([
            'kategori1' => 'required|in:Learning,Project,DiKantor,Keluar Dengan Teknisi',
            'report1' => 'required',
            'kategori2' => 'nullable|in:Learning,Project,DiKantor,Keluar Dengan Teknisi',
            'report2' => 'nullable'
        ]);

        Siswa::create([
            'kategori' => $request->kategori1,
            'report' => $request->report1,
            'status' => 'to do',
        ]);

        if ($request->filled('kategori2') && $request->filled('report2')) {
            Siswa::create([
                'kategori' => $request->kategori2,
                'report' => $request->report2,
                'status' => 'to do',
            ]);
        }

        return redirect()->route('siswarpl.index')->with('success', 'Laporan berhasil ditambahkan.');
    }

    public function toggle($id)
    {
        $siswa = Siswa::findOrFail($id);

        if ($siswa->status === 'to do') {
            $siswa->waktu_mulai = Carbon::now();
            $siswa->status = 'doing';
        } elseif ($siswa->status === 'doing') {
            $siswa->waktu_selesai = Carbon::now();
            $siswa->status = 'done';
        }

        $siswa->save();

        return redirect()->back()->with('success', 'Status berhasil diubah.');
    }
}
