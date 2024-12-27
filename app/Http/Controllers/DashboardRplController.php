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

        $chartData = [
            'labels' => ['Project'],
            'datasets' => [
                [
                    'data' => [$persentaseLearning, $persentaseProject],
                    'backgroundColor' => ['#FF9F43', '#42A5F5'],
                    'hoverBackgroundColor' => ['#FF7043', '#1E88E5'],
                ],
            ],
        ];

        $siswaDataLearning = Siswa::where('user_id', $userId)
            ->where('kategori', 'Learning')
            ->get()
            ->groupBy('materi_id')
            ->map(function ($items) use ($totalWaktuLearning) {
                $totalTime = $items->sum(function ($item) {
                    if ($item->waktu_mulai && $item->waktu_selesai) {
                        $waktuMulai = Carbon::parse($item->waktu_mulai);
                        $waktuSelesai = Carbon::parse($item->waktu_selesai);

                        return $waktuSelesai->greaterThan($waktuMulai) ? $waktuSelesai->diffInSeconds($waktuMulai) : 0;
                    }

                    return 0;
                });

                $percentage = $totalWaktuLearning ? ($totalTime / $totalWaktuLearning) * 100 : 0;

                return ['totalTime' => $totalTime, 'percentage' => $percentage];
            });

        $materiNamesLearning = Materi::whereIn('id', $siswaDataLearning->keys())->pluck('materi', 'id');

        $learningChartData = [
            'labels' => $materiNamesLearning->values(),
            'datasets' => [
                [
                    'data' => $siswaDataLearning->pluck('percentage')->values(),
                    'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                ],
            ],
        ];

        $activityData = $jumlahAktivitasLearning;

        return view('dashboardrpl', compact(
            'jumlahDataProject',
            'jumlahDataLearning',
            'totalWaktu',
            'persentaseLearning',
            'persentaseProject',
            'chartData',
            'learningChartData',
            'activityData',
            'aktivitasNames',
            'siswaData',
            'chartData',
            'activityData'
        ));
    }
}
