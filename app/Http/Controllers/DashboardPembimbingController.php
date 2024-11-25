<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Materi;
use App\Models\Aktivitas;
use Carbon\Carbon;

class DashboardPembimbingController extends Controller
{
    public function index()
    {
        // Ambil jumlah siswa per jurusan
        $rplCount = Materi::where('jurusan', 'RPL')->count();
        $tkjCount = Materi::where('jurusan', 'TKJ')->count();

        // Data untuk chart siswa berdasarkan jurusan
        $chartData = [
            'labels' => ['RPL', 'TKJ'],
            'datasets' => [
                [
                    'data' => [$rplCount, $tkjCount],
                    'backgroundColor' => ['#36A2EB', '#FF6384'],
                    'hoverOffset' => 15,
                ]
            ]
        ];

        // Data aktivitas siswa
        $activityData = Siswa::select('kategori')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('kategori')
            ->pluck('count', 'kategori');

        // Total waktu berdasarkan kategori
        $totalWaktuPerKategori = Siswa::select('kategori')
            ->selectRaw('SUM(TIMESTAMPDIFF(SECOND, waktu_mulai, waktu_selesai)) as total_waktu')
            ->groupBy('kategori')
            ->pluck('total_waktu', 'kategori');

        return view('dashboard', compact('chartData', 'activityData', 'totalWaktuPerKategori', 'rplCount', 'tkjCount'));
    }
}
