<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use Carbon\Carbon;

class SiswaRplController extends Controller
{
    public function index(Request $request)
{
    // Get the 'status' query parameter, default to 'all' if not set
    $statusFilter = $request->get('status', 'all');

    // Query based on the selected filter
    if ($statusFilter === 'all') {
        // If 'All' is selected, fetch all entries
        $siswarpl = Siswa::all();
    } else {
        // Filter by status
        $siswarpl = Siswa::where('status', $statusFilter)->get();
    }

    // Map over the data to calculate the total time if applicable
    $siswarpl = $siswarpl->map(function ($item) {
        if ($item->waktu_mulai && $item->waktu_selesai) {
            $waktuMulai = Carbon::parse($item->waktu_mulai);
            $waktuSelesai = Carbon::parse($item->waktu_selesai);
            $item->total_waktu = $waktuSelesai->diff($waktuMulai)->format('%H:%I:%S');
        } else {
            $item->total_waktu = '-';
        }
        return $item;
    });

    // Return the view with the filtered data
    return view('monitoring_siswa.siswarpl', compact('siswarpl'));
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

    return redirect()->route('siswarpl.index')->with('success', 'Aktivitas Telah Diselesaikan');
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



    public function start($id)
    {
        $siswarpl = Siswa::findOrFail($id);
        $siswarpl->waktu_mulai = Carbon::now();
        $siswarpl->status = 'doing'; 
        $siswarpl->save();
    
        return redirect()->back()->with('success', 'Waktu mulai berhasil diupdate.');
    }
    
    public function stop($id)
    {
        $siswarpl = Siswa::findOrFail($id);
        $siswarpl->waktu_selesai = Carbon::now(); 
        $siswarpl->status = 'done';
        $siswarpl->save();
    
        return redirect()->back()->with('success', 'Waktu berhenti berhasil diupdate.');
    }

    public function toggle($id)
    {
        $siswarpl = Siswa::findOrFail($id);

        if ($siswarpl->status === 'to do') {
            $siswarpl->waktu_mulai = Carbon::now();
            $siswarpl->status = 'doing';
        } elseif ($siswarpl->status === 'doing') {
            $siswarpl->waktu_selesai = Carbon::now();
            $siswarpl->status = 'done';
        }

        $siswarpl->save();

        return redirect()->back()->with('success', 'Status berhasil diperbarui.');
    }
}