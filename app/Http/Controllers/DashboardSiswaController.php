<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\siswa;
use App\Models\Aktivitas;
use Illuminate\Support\Facades\Auth;

class DashboardSiswaController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        $totalWaktuTeknisi = Siswa::where('user_id', $userId)
            ->where('kategori', 'Keluar Dengan Teknisi')
            ->get()
            ->reduce(function ($carry, $item) {
                if ($item->waktu_mulai && $item->waktu_selesai) {
                    $waktuMulai = Carbon::parse($item->waktu_mulai);
                    $waktuSelesai = Carbon::parse($item->waktu_selesai);
                    $carry += $waktuSelesai->diffInMinutes($waktuMulai);
                }
                return $carry;
            }, 0);

        $siswaData = Siswa::where('user_id', $userId)
            ->where('kategori', 'Keluar Dengan Teknisi')
            ->get()
            ->groupBy('aktivitas_id')
            ->map(function ($items, $aktivitasId) use ($totalWaktuTeknisi) {
                $totalTime = 0;
                foreach ($items as $item) {
                    if ($item->waktu_mulai && $item->waktu_selesai) {
                        $waktuMulai = Carbon::parse($item->waktu_mulai);
                        $waktuSelesai = Carbon::parse($item->waktu_selesai);
                        $totalTime += $waktuSelesai->diffInMinutes($waktuMulai);
                    }
                }
                $percentage = $totalWaktuTeknisi ? ($totalTime / $totalWaktuTeknisi) * 100 : 0;
                return ['totalTime' => $totalTime, 'percentage' => $percentage];
            });

        $aktivitasNames = Aktivitas::whereIn('id', $siswaData->keys())->pluck('nama_aktivitas', 'id');

        return view('dashboard_siswa', compact('siswaData', 'aktivitasNames', 'totalWaktuTeknisi'));
    }


}