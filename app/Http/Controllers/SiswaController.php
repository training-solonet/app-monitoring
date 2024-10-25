<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use Carbon\Carbon;

class SiswaController extends Controller
{
    public function index() {
        $siswa = Siswa::all();
        return view('monitoring_siswa.siswa', compact('siswa'));
    }

    public function storeMultiple(Request $request)
    {
        $request->validate([
            'kategori1' => 'required|in:DiKantor,Keluar Dengan Teknisi',
            'report1' => 'required',
            'kategori2' => 'nullable|in:DiKantor,Keluar Dengan Teknisi',
            'report2' => 'nullable'
        ]);

        Siswa::create([
            'kategori' => $request->kategori1,
            'report' => $request->report1,
        ]);

        if ($request->filled('kategori2') && $request->filled('report2')) {
            Siswa::create([
                'kategori' => $request->kategori2,
                'report' => $request->report2,
            ]);
        }

        return redirect()->route('siswa.index')->with('success', 'Laporan berhasil ditambahkan.');
    }

    public function start($id)
{
    $siswa = Siswa::findOrFail($id);
    $siswa->waktu_mulai = Carbon::now();
    $siswa->status = 'doing'; // atau status lain yang diinginkan
    $siswa->save();

    return redirect()->back()->with('success', 'Waktu mulai berhasil diupdate.');
}
}
