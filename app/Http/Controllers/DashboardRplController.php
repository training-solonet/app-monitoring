<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Siswa;
use App\Models\Aktivitas;
use App\Models\Materi;
use Illuminate\Support\Facades\Auth;

class DashboardRplController extends Controller
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
                    if ($waktuSelesai->greaterThan($waktuMulai)) {
                        $carry += $waktuSelesai->diffInSeconds($waktuMulai);
                    }
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
                        if ($waktuSelesai->greaterThan($waktuMulai)) {
                            $totalTime += $waktuSelesai->diffInSeconds($waktuMulai);
                        }
                    }
                }
                $percentage = $totalWaktuTeknisi ? ($totalTime / $totalWaktuTeknisi) * 100 : 0;
                return ['totalTime' => $totalTime, 'percentage' => $percentage];
            });

        $aktivitasNames = Aktivitas::whereIn('id', $siswaData->keys())->pluck('nama_aktivitas', 'id');

        $jumlahAktivitas = Siswa::where('user_id', $userId)
            ->where('kategori', 'Keluar Dengan Teknisi')
            ->get()
            ->groupBy('aktivitas_id')
            ->map->count();

            $totalWaktuLearning = Siswa::where('user_id', $userId)
            ->where('kategori',  'Learning')
            ->get()
            ->reduce(function ($carry, $item) {
                if ($item->waktu_mulai && $item->waktu_selesai) {
                    $waktuMulai = Carbon::parse($item->waktu_mulai);
                    $waktuSelesai = Carbon::parse($item->waktu_selesai);
                    if ($waktuSelesai->greaterThan($waktuMulai)) {
                        $carry += $waktuSelesai->diffInSeconds($waktuMulai);
                    }
                }
                return $carry;
            }, 0);

        $siswaDataLearning = Siswa::where('user_id', $userId)
            ->where('kategori', 'Learning')
            ->get()
            ->groupBy('materi_id')
            ->map(function ($items, $materiId) use ($totalWaktuLearning) {
                $totalTime = 0;
                foreach ($items as $item) {
                    if ($item->waktu_mulai && $item->waktu_selesai) {
                        $waktuMulai = Carbon::parse($item->waktu_mulai);
                        $waktuSelesai = Carbon::parse($item->waktu_selesai);
                        if ($waktuSelesai->greaterThan($waktuMulai)) {
                            $totalTime += $waktuSelesai->diffInSeconds($waktuMulai);
                        }
                    }
                }
                $percentage = $totalWaktuLearning ? ($totalTime / $totalWaktuLearning) * 100 : 0;
                return ['totalTime' => $totalTime, 'percentage' => $percentage];
            });

        $materiNames = Materi::whereIn('id', $siswaDataLearning->keys())->pluck('materi', 'id');

        $jumlahAktivitasLearning = Siswa::where('user_id', $userId)
            ->where('kategori', 'Learning')
            ->get()
            ->groupBy('materi_id')
            ->map->count();

        return view('dashboard_siswa', compact('siswaData', 'aktivitasNames', 'totalWaktuTeknisi', 'jumlahAktivitas','siswaDataLearning', 'materiNames', 'totalWaktuLearning', 'jumlahAktivitasLearning'));
    }
}
