<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\Siswa;
use Carbon\Carbon;

class DashboardPembimbingController extends Controller
{
    public function index()
    {
        $rplCount = Materi::where('jurusan', 'RPL')->count();
        $tkjCount = Materi::where('jurusan', 'TKJ')->count();

        $chartData = [
            'labels' => ['RPL', 'TKJ'],
            'datasets' => [
                [
                    'data' => [$rplCount, $tkjCount],
                    'backgroundColor' => ['#36A2EB', '#FF6384'],
                    'hoverOffset' => 15,
                ],
            ],
        ];

        $activityData = Siswa::select('kategori')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('kategori')
            ->pluck('count', 'kategori');

        $totalWaktuPerKategori = Siswa::select('kategori')
            ->selectRaw('SUM(TIMESTAMPDIFF(SECOND, waktu_mulai, waktu_selesai)) as total_waktu')
            ->groupBy('kategori')
            ->pluck('total_waktu', 'kategori');

        $jumlahDataRPL = Siswa::where('user_id')
            ->whereIn('kategori', ['Dikantor', 'Keluar Dengan Teknisi'])
            ->count();

        $jumlahDataTKJ = Siswa::where('user_id')
            ->whereIn('kategori', ['Learning', 'Project'])
            ->count();

        $totalWaktu = Siswa::where('user_id')
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

        $totalAktivitas = $jumlahDataTKJ + $jumlahDataRPL;

        $persentaseTKJ = $totalAktivitas > 0 ? ($jumlahDataTKJ / $totalAktivitas) * 100 : 0;
        $persentaseRPL = $totalAktivitas > 0 ? ($jumlahDataRPL / $totalAktivitas) * 100 : 0;

        return view('dashboard', compact('chartData', 'activityData', 'totalWaktuPerKategori', 'rplCount', 'tkjCount', 'jumlahDataRPL', 'jumlahDataTKJ', 'totalWaktu', 'persentaseTKJ', 'persentaseRPL'));
    }
}
