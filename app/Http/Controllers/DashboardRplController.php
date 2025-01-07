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

        $totalWaktuProjek = Siswa::where('user_id', $userId)
            ->where('kategori', 'Projek')
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
            ->where('kategori', 'Projek')
            ->get()
            ->groupBy('aktivitas_id')
            ->map(function ($items, $aktivitasId) use ($totalWaktuProjek) {
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
                $percentage = $totalWaktuProjek ? ($totalTime / $totalWaktuProjek) * 100 : 0;

                return ['totalTime' => $totalTime, 'percentage' => $percentage];
            });

        $aktivitasNames = Aktivitas::whereIn('id', $siswaData->keys())->pluck('nama_aktivitas', 'id');

        $jumlahAktivitas = Siswa::where('user_id', $userId)
            ->where('kategori', 'Projek')
            ->get()
            ->groupBy('aktivitas_id')
            ->map->count();

        $totalWaktuBelajar = Siswa::where('user_id', $userId)
            ->where('kategori', 'Belajar')
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

        $siswaDataBelajar = Siswa::where('user_id', $userId)
            ->where('kategori', 'Belajar')
            ->get()
            ->groupBy('materi_id')
            ->map(function ($items, $materiId) use ($totalWaktuBelajar) {
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
                $percentage = $totalWaktuBelajar ? ($totalTime / $totalWaktuBelajar) * 100 : 0;

                return ['totalTime' => $totalTime, 'percentage' => $percentage];
            });

        $materiNames = Materi::whereIn('id', $siswaDataBelajar->keys())->pluck('materi', 'id');

        $jumlahAktivitasBelajar = Siswa::where('user_id', $userId)
            ->where('kategori', 'Belajar')
            ->get()
            ->groupBy('materi_id')
            ->map->count();

        $jumlahDataProjek = Siswa::where('user_id', $userId)
            ->where('kategori', 'Projek')
            ->count();

        $jumlahDataBelajar = Siswa::where('user_id', $userId)
            ->where('kategori', 'Belajar')
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

        $totalAktivitas = $jumlahDataBelajar + $jumlahDataProjek;

        $persentaseBelajar = $totalAktivitas > 0 ? ($jumlahDataBelajar / $totalAktivitas) * 100 : 0;
        $persentaseProjek = $totalAktivitas > 0 ? ($jumlahDataProjek / $totalAktivitas) * 100 : 0;

        // print_r($materiNames);
        // print_r($jumlahAktivitasBelajar);

        $dataAktivitasBelajar = [
            'name' => [],
            'jumlah' => [],
        ];

        $i = 0;
        foreach ($materiNames as $key => $val) {
            $dataAktivitasBelajar['name'][$i] = $val;
            $dataAktivitasBelajar['jumlah'][$i] = $jumlahAktivitasBelajar[$key];
            $i++;
        }

        return view('dashboardrpl', compact(
            'siswaData',
            'aktivitasNames',
            'totalWaktuProjek',
            'jumlahAktivitas',
            'siswaDataBelajar',
            'materiNames',
            'totalWaktuBelajar',
            'jumlahAktivitasBelajar',
            'jumlahDataBelajar',
            'jumlahDataProjek',
            'totalWaktu',
            'persentaseBelajar',
            'persentaseProjek',
            'dataAktivitasBelajar'
        ));
    }
}
