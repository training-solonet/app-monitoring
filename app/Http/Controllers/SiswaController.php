<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\materi;
use Carbon\Carbon;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->get('status', 'all');
    
        if ($statusFilter === 'all') {
            $siswa = Siswa::all();
        } else {
            $siswa = Siswa::where('status', $statusFilter)->get();
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
    
        $materi = Materi::all();
        return view('monitoring_siswa.siswa', compact('siswa', 'materi'));
    }
    
    public function updateTime(Request $request, $id)
    {
        $item = Siswa::findOrFail($id);
    
        $request->validate([
            'waktu_selesai' => 'required|date_format:H:i', 
        ]);
    
        $currentDate = \Carbon\Carbon::parse($item->waktu_mulai)->format('Y-m-d'); 
    
        $newWaktuSelesai = $currentDate . ' ' . $request->waktu_selesai; 
    
        $item->update([
            'waktu_selesai' => $newWaktuSelesai, 
            'status' => 'done', 
        ]);
    
        return redirect()->route('siswa.index')->with('success', 'Aktivitas Telah Diselesaikan');
    }

    public function storeMultiple(Request $request)
    {
        $request->validate([
            'kategori1' => 'required|in:Learning,Project,DiKantor,Keluar Dengan Teknisi',
            'report1' => 'required',
            'materi_id1' => 'required|exists:materi,id', // Validasi foreign key untuk materi pertama
            'kategori2' => 'nullable|in:Learning,Project,DiKantor,Keluar Dengan Teknisi',
            'report2' => 'nullable',
            'materi_id2' => 'nullable|exists:materi,id' // Validasi foreign key untuk materi kedua
        ]);
    
        // Menyimpan aktivitas pertama dengan status default 'to do'
        Siswa::create([
            'kategori' => $request->kategori1,
            'report' => $request->report1,
            'materi_id' => $request->materi_id1, // Menyimpan foreign key
            'status' => 'to do', // Status default
        ]);
    
        // Menyimpan aktivitas kedua, jika ada
        if ($request->filled('kategori2') && $request->filled('report2') && $request->filled('materi_id2')) {
            Siswa::create([
                'kategori' => $request->kategori2,
                'report' => $request->report2,
                'materi_id' => $request->materi_id2, // Menyimpan foreign key
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
