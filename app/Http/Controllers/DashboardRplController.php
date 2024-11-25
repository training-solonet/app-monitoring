<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Siswa;
use App\Models\Materi;
use Illuminate\Support\Facades\Auth;

class DashboardRplController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        $jumlahDataProject = Siswa::where('user_id', $userId)
        ->where('kategori', 'Project')
        ->count();

        $jumlahDataLearning = Siswa::where('user_id', $userId)
        ->where('kategori', 'Learning')
        ->count();

        $totalWaktu = Siswa::where('user_id', $userId)
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

        $totalAktivitas = $jumlahDataLearning + $jumlahDataProject;

        $persentaseLearning = $totalAktivitas > 0 ? ($jumlahDataLearning / $totalAktivitas) * 100 : 0;
        $persentaseProject = $totalAktivitas > 0 ? ($jumlahDataProject / $totalAktivitas) * 100 : 0;

        return view('dashboardrpl', compact('jumlahDataProject', 'jumlahDataLearning', 'totalWaktu', 'persentaseLearning', 'persentaseProject'));
    }
}
