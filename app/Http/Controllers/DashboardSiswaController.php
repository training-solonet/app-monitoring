<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Siswa;
use App\Models\Materi;
use App\Models\Aktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardSiswaController extends Controller
{
    public function index(Request $request)
    {
        // Menyimpan ID pengguna yang sedang login. ID ini diperoleh menggunakan
        $userId = Auth::id();

        // Menyimpan total waktu (dalam detik) yang dihabiskan oleh siswa pada aktivitas dengan kategori "Keluar Dengan Teknisi"
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

        // Menyimpan data waktu dan persentase waktu untuk setiap aktivitas berdasarkan kategori "Keluar Dengan Teknisi".
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

        //  Mengambil nama aktivitas berdasarkan id aktivitas yang terdapat dalam $siswaData.
        $aktivitasNames = Aktivitas::whereIn('id', $siswaData->keys())->pluck('nama_aktivitas', 'id');

        // Menyimpan jumlah data aktivitas untuk kategori "Keluar Dengan Teknisi", dikelompokkan berdasarkan aktivitas_id.
        $jumlahAktivitas = Siswa::where('user_id', $userId)
            ->where('kategori', 'Keluar Dengan Teknisi')
            ->get()
            ->groupBy('aktivitas_id')
            ->map->count();

        // Menyimpan total waktu (dalam detik) yang dihabiskan oleh siswa pada aktivitas dengan kategori "DiKantor".
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

        //  Menyimpan data waktu dan persentase waktu untuk setiap materi berdasarkan kategori "DiKantor".
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

        // Mengambil nama materi berdasarkan id materi yang terdapat dalam $siswaDataDikantor.
        $materiNames = Materi::whereIn('id', $siswaDataDikantor->keys())->pluck('materi', 'id');

        // Menyimpan jumlah data aktivitas untuk kategori "DiKantor", dikelompokkan berdasarkan materi_id.
        $jumlahAktivitasDikantor = Siswa::where('user_id', $userId)
            ->where('kategori', 'DiKantor')
            ->get()
            ->groupBy('materi_id')
            ->map->count();

        // Menyimpan jumlah total data siswa untuk kategori "Keluar Dengan Teknisi".
        $jumlahDataTeknisi = Siswa::where('user_id', $userId)
            ->where('kategori', 'Keluar Dengan Teknisi')
            ->count();

        // Menyimpan jumlah total data siswa untuk kategori "DiKantor".
        $jumlahDataDikantor = Siswa::where('user_id', $userId)
            ->where('kategori', 'DiKantor')
            ->count();

        // Menyimpan total waktu keseluruhan (dalam detik) untuk semua kategori, baik "Keluar Dengan Teknisi" maupun "DiKantor".
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

        // Menyimpan total jumlah aktivitas untuk semua kategori (gabungan dari $jumlahDataTeknisi dan $jumlahDataDikantor).
        $totalAktivitas = $jumlahDataDikantor + $jumlahDataTeknisi;

        //  Menyimpan persentase jumlah aktivitas kategori "DiKantor" terhadap total aktivitas.
        $persentaseDikantor = $totalAktivitas > 0 ? ($jumlahDataDikantor / $totalAktivitas) * 100 : 0;
        // Menyimpan persentase jumlah aktivitas kategori "Keluar Dengan Teknisi" terhadap total aktivitas.
        $persentaseTeknisi = $totalAktivitas > 0 ? ($jumlahDataTeknisi / $totalAktivitas) * 100 : 0;

        $belumLapor = DB::table('siswa')
                        ->where('user_id', Auth::id())
                        ->where('report_status', 'Belum Lapor')
                        ->count();

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
            'persentaseTeknisi',
            'belumLapor'
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
