<?php

namespace App\Http\Controllers;

use App\Models\Aktivitas;
use App\Models\Materi;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
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

        $totalWaktuDikantor = Siswa::where('user_id', $userId)
            ->where('kategori', 'DiKantor')
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

        $siswaDataDikantor = Siswa::where('user_id', $userId)
            ->where('kategori', 'DiKantor')
            ->get()
            ->groupBy('materi_id')
            ->map(function ($items, $materiId) use ($totalWaktuDikantor) {
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
                $percentage = $totalWaktuDikantor ? ($totalTime / $totalWaktuDikantor) * 100 : 0;

                return ['totalTime' => $totalTime, 'percentage' => $percentage];
            });

        $materiNames = Materi::whereIn('id', $siswaDataDikantor->keys())->pluck('materi', 'id');

        $jumlahAktivitasDikantor = Siswa::where('user_id', $userId)
            ->where('kategori', 'DiKantor')
            ->get()
            ->groupBy('materi_id')
            ->map->count();

        $jumlahDataTeknisi = Siswa::where('user_id', $userId)
            ->where('kategori', 'Keluar Dengan Teknisi')
            ->count();

        $jumlahDataDikantor = Siswa::where('user_id', $userId)
            ->where('kategori', 'DiKantor')
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

        $totalAktivitas = $jumlahDataDikantor + $jumlahDataTeknisi;

        $persentaseDikantor = $totalAktivitas > 0 ? ($jumlahDataDikantor / $totalAktivitas) * 100 : 0;
        $persentaseTeknisi = $totalAktivitas > 0 ? ($jumlahDataTeknisi / $totalAktivitas) * 100 : 0;

        return view('dashboard_siswa', compact(
            'siswaData',
            'aktivitasNames',
            'totalWaktuTeknisi',
            'jumlahAktivitas',
            'siswaDataDikantor',
            'materiNames',
            'totalWaktuDikantor',
            'jumlahAktivitasDikantor',
            'jumlahDataDikantor',
            'jumlahDataTeknisi',
            'totalWaktu',
            'persentaseDikantor',
            'persentaseTeknisi'
        ));
    }

    //     public function getSiswaData($userId)
    // {
    //     $siswaData = Siswa::where('user_id', $userId)->get()->groupBy('aktivitas_id');

    //     $aktivitasNames = Aktivitas::whereIn('id', $siswaData->keys())->pluck('nama_aktivitas', 'id');

    //     $data = [];
    //     foreach ($siswaData as $key => $items) {
    //         $totalTime = 0;
    //         foreach ($items as $item) {
    //             $waktuMulai = Carbon::parse($item->waktu_mulai);
    //             $waktuSelesai = Carbon::parse($item->waktu_selesai);
    //             if ($waktuSelesai->greaterThan($waktuMulai)) {
    //                 $totalTime += $waktuSelesai->diffInSeconds($waktuMulai);
    //             }
    //         }
    //         $data[] = [
    //             'label' => $aktivitasNames[$key],
    //             'value' => $totalTime
    //         ];
    //     }

    //     return response()->json([
    //         'labels' => array_column($data, 'label'),
    //         'values' => array_column($data, 'value')
    //     ]);
    // }

}
