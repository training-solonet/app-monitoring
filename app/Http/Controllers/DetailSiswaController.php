<?php

namespace App\Http\Controllers;

use App\Models\Aktivitas;
use App\Models\Materi;
use App\Models\Siswa;
use Carbon\Carbon;

class DetailSiswaController extends Controller
{
    public function index()
    {
        // Menyimpan jumlah total materi yang ada dalam tabel Materi.
        $totalMateri = Materi::count();
        // Menyimpan jumlah total aktivitas yang ada dalam tabel Aktivitas.
        $totalAktivitas = Aktivitas::count();

        //  Menyimpan total waktu yang dihitung dalam menit untuk semua entri Siswa yang memiliki waktu mulai (waktu_mulai) dan waktu selesai (waktu_selesai).
        $totalWaktu = Siswa::whereNotNull('waktu_mulai')
            ->whereNotNull('waktu_selesai')
            ->get()
            ->map(function ($siswa) {
                $waktuMulai = Carbon::parse($siswa->waktu_mulai);
                $waktuSelesai = Carbon::parse($siswa->waktu_selesai);

                return $waktuSelesai->diffInMinutes($waktuMulai);
            })
            ->sum();

        //  Menyimpan semua data siswa yang ada dalam tabel Siswa.
        $siswa = Siswa::all();

        return view('detail', compact('totalMateri', 'totalAktivitas', 'totalWaktu', 'siswa'));
    }
}
