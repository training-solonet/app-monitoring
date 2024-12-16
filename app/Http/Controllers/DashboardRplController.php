<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardRplController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Jumlah data berdasarkan kategori
        $jumlahDataProject = Siswa::where('user_id', $userId)
            ->where('kategori', 'Project')
            ->count();

        $jumlahDataLearning = Siswa::where('user_id', $userId)
            ->where('kategori', 'Learning')
            ->count();

        // Total waktu dalam detik
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

        // Persentase
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
