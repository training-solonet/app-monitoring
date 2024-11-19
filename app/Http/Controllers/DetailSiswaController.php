<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Materi;
use App\Models\Aktivitas;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DetailSiswaController extends Controller
{
    public function index()
    {
        $totalMateri = Materi::count();
        $totalAktivitas = Aktivitas::count();
    
        $totalWaktu = Siswa::whereNotNull('waktu_mulai')
            ->whereNotNull('waktu_selesai')
            ->get()
            ->map(function ($siswa) {
                $waktuMulai = Carbon::parse($siswa->waktu_mulai);
                $waktuSelesai = Carbon::parse($siswa->waktu_selesai);
                return $waktuSelesai->diffInMinutes($waktuMulai);
            })
            ->sum();

        $siswa = Siswa::all();

        return view('detail', compact('totalMateri', 'totalAktivitas', 'totalWaktu', 'siswa'));
    }
}
