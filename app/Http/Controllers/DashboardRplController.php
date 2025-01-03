<?php

namespace App\Http\Controllers;

use App\Models\Aktivitas;
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

        $totalWaktuProject = Siswa::where('user_id', $userId)
            ->where('kategori', 'Project')
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
            ->where('kategori', 'Project')
            ->get()
            ->groupBy('aktivitas_id')
            ->map(function ($items, $aktivitasId) use ($totalWaktuProject) {
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
                $percentage = $totalWaktuProject ? ($totalTime / $totalWaktuProject) * 100 : 0;

                return ['totalTime' => $totalTime, 'percentage' => $percentage];
            });

        $aktivitasNames = Aktivitas::whereIn('id', $siswaData->keys())->pluck('nama_aktivitas', 'id');

        $jumlahAktivitas = Siswa::where('user_id', $userId)
            ->where('kategori', 'Project')
            ->get()
            ->groupBy('aktivitas_id')
            ->map->count();

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

        $materiNames = Materi::whereIn('id', $siswaDataLearning->keys())->pluck('materi', 'id');

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

        // print_r($materiNames);
        // print_r($jumlahAktivitasLearning);

        $i = 0;
        foreach ($materiNames as $key => $val) {
            $dataAktivitasLearning['name'][$i] = $val;
            $dataAktivitasLearning['jumlah'][$i] = $jumlahAktivitasLearning[$key];
            $i++;
        }

        return view('dashboardrpl', compact(
            'siswaData',
            'aktivitasNames',
            'totalWaktuProject',
            'jumlahAktivitas',
            'siswaDataLearning',
            'materiNames',
            'totalWaktuLearning',
            'jumlahAktivitasLearning',
            'jumlahDataLearning',
            'jumlahDataProject',
            'totalWaktu',
            'persentaseLearning',
            'persentaseProject',
            'dataAktivitasLearning'
        ));
    }
}
