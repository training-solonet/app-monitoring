<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use Carbon\Carbon;

class SiswaRplController extends Controller
{
    public function index()
    {
        $siswa = Siswa::all();
        return view('monitoring_siswa.siswarpl', compact('siswa'));
    }

    public function storeMultiple(Request $request)
{
    $request->validate([
        'kategori1' => 'required|in:DiKantor,Keluar Dengan Teknisi',
        'report1' => 'required',
        'kategori2' => 'nullable|in:DiKantor,Keluar Dengan Teknisi',
        'report2' => 'nullable'
    ]);

    // Menyimpan aktivitas pertama dengan status default 'to do'
    Siswa::create([
        'kategori' => $request->kategori1,
        'report' => $request->report1,
        'status' => 'to do', // Status default
    ]);

    // Menyimpan aktivitas kedua, jika ada
    if ($request->filled('kategori2') && $request->filled('report2')) {
        Siswa::create([
            'kategori' => $request->kategori2,
            'report' => $request->report2,
            'status' => 'to do', // Status default
        ]);
    }

    return redirect()->route('siswa.index')->with('success', 'Laporan berhasil ditambahkan.');
}



    public function start($id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->waktu_mulai = Carbon::now();
        $siswa->status = 'doing'; 
        $siswa->save();
    
        return redirect()->back()->with('success', 'Waktu mulai berhasil diupdate.');
    }
    
    public function stop($id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->waktu_selesai = Carbon::now(); 
        $siswa->status = 'done';
        $siswa->save();
    
        return redirect()->back()->with('success', 'Waktu berhenti berhasil diupdate.');
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

        return redirect()->back()->with('success', 'Status berhasil diperbarui.');
    }
}
