<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardRplController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        $totalWaktuLearning = Siswa::where('user_id', $userId)
            ->where('kategori', 'Learning')
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

        $materiNamesLearning = Materi::whereIn('id', $siswaDataLearning->keys())->pluck('materi', 'id');

        $jumlahAktivitasLearning = Siswa::where('user_id', $userId)
            ->where('kategori', 'Learning')
            ->get()
            ->groupBy('materi_id')
            ->map->count();

        $jumlahDataProject = Siswa::where('user_id', $userId)
            ->where('kategori', 'Project')
            ->count();

        $jumlahDataLearning = Siswa::where('user_id', $userId)
            ->where('kategori', 'Learning')
            ->count();

        $siswaData = Siswa::where('user_id', $userId)
            ->get()
            ->map(function ($item) {
                $totalTime = 0;
                if ($item->waktu_mulai && $item->waktu_selesai) {
                    $waktuMulai = Carbon::parse($item->waktu_mulai);
                    $waktuSelesai = Carbon::parse($item->waktu_selesai);
                    if ($waktuSelesai->greaterThan($waktuMulai)) {
                        $totalTime = $waktuSelesai->diffInSeconds($waktuMulai);
                    }
                }

                return [
                    'name' => $item->aktivitas_name,
                    'totalTime' => $totalTime,
                ];
            });

        $totalWaktu = $siswaData->sum('totalTime');
        $aktivitasNames = $siswaData->pluck('name');
        $totalAktivitas = $jumlahDataLearning + $jumlahDataProject;

        $persentaseLearning = $totalAktivitas > 0 ? ($jumlahDataLearning / $totalAktivitas) * 100 : 0;
        $persentaseProject = $totalAktivitas > 0 ? ($jumlahDataProject / $totalAktivitas) * 100 : 0;

        return view('dashboardrpl', compact(
            'jumlahDataProject',
            'jumlahDataLearning',
            'totalWaktu',
            'persentaseLearning',
            'persentaseProject',
            'aktivitasNames',
            'siswaData'
        ));
    }
}
